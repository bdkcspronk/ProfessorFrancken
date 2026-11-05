<?php

declare(strict_types=1);

namespace Francken\Association\Activities;

use Carbon\Carbon;
use DateTimeImmutable;
use Francken\Association\LegacyMember;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;

final class Activity extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'association_activities';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'slug',
        'summary',
        'source_content',
        'compiled_content',
        'location',
        'start_date',
        'end_date',
        'google_calendar_uid',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * The address of a map of the location of the activity, or null when there is no location or
     * no key for Google Maps (GOOGLE_MAPS_EMBED_KEY in .env)
     */
    public function getGoogleMapsEmbedUriAttribute() : ?string
    {
        $key = config('services.google_maps.embed_key');

        if ( ! is_string($key) || $key === '' || trim((string) $this->location) === '') {
            return null;
        }

        return 'https://www.google.com/maps/embed/v1/place?' . http_build_query([
            'q' => $this->location,
            'zoom' => 13,
            'key' => $key,
        ]);
    }

    public function getRegistrationDeadlineAttribute() : Carbon
    {
        if ($this->signUpSettings !== null) {
            return $this->signUpSettings->deadline_at;
        }

        return $this->end_date;
    }

    public function scopeAfter(Builder $query, DateTimeImmutable $date) : Builder
    {
        return $query->where('end_date', '>', $date);
    }

    public function getScheduleAttribute() : string
    {
        $string = '';
        $from = $this->start_date;
        $to = $this->end_date;
        $start = $this->start_date;
        $end = $this->end_date;

        $string .= $start->format('d');

        // Display month and year only twice if necessary
        if ($from->month !== $to->month) {
            $string .= $start->format(' F');
        }

        // Check if the end date is different
        if ($from->format('Y-m-d') !== $to->format('Y-m-d')) {
            $string .= ' - ' . $end->format('d F');
        } else {
            $string .= $end->format(' F');
        }

        // Show time if the activity isn't on the whole day
        if ($this->start_date->format('H:i') !== '00:00') {
            $string .= ' at ' . $start->format('H:i');
        }

        return $string;
    }

    public function signUpSettings() : HasOne
    {
        return $this->hasOne(SignUpSettings::class);
    }

    public function signUps() : HasMany
    {
        return $this->hasMany(SignUp::class);
    }

    public function comments() : HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * The number of people (members and their plus ones) that take up a place:
     * those with a place and those that have been offered one.
     * This does not count the people on the waiting list.
     */
    public function getTotalSignUpsAttribute() : int
    {
        return $this->signUps
            ->filter(fn (SignUp $signUp) : bool => $signUp->has_place)
            ->sum(fn (SignUp $signUp) : int => $signUp->people);
    }

    /**
     * The sign ups that take up a place (including those offered a place),
     * in the order in which members signed up
     *
     * @return Collection<int, SignUp>
     */
    public function getPlacedSignUpsAttribute() : Collection
    {
        return $this->signUps
            ->filter(fn (SignUp $signUp) : bool => $signUp->has_place)
            ->sortBy('id')
            ->values()
            ->toBase();
    }

    /**
     * The sign ups that have been offered a place, which still has to be accepted
     *
     * @return Collection<int, SignUp>
     */
    public function getOfferedSignUpsAttribute() : Collection
    {
        return $this->placed_sign_ups
            ->filter(fn (SignUp $signUp) : bool => $signUp->is_offered)
            ->values();
    }

    /**
     * The sign ups on the waiting list, in the order in which members signed up
     *
     * @return Collection<int, SignUp>
     */
    public function getWaitingListSignUpsAttribute() : Collection
    {
        return $this->signUps
            ->filter(fn (SignUp $signUp) : bool => $signUp->is_waiting)
            ->sortBy('id')
            ->values()
            ->toBase();
    }

    /**
     * The number of places that nobody has taken yet, or null when there is no maximum
     */
    public function getPlacesLeftAttribute() : ?int
    {
        if ($this->signUpSettings === null || $this->signUpSettings->max_sign_ups === null) {
            return null;
        }

        return max(0, $this->signUpSettings->max_sign_ups - $this->total_sign_ups);
    }

    /**
     * Whether new sign ups go on the waiting list: when there is no place left,
     * or when others are already waiting (so that nobody skips the queue)
     */
    public function getIsFullAttribute() : bool
    {
        $placesLeft = $this->places_left;

        if ($placesLeft === null) {
            return false;
        }

        return $placesLeft === 0 || $this->waiting_list_sign_ups->isNotEmpty();
    }

    public function isOnWaitingList(SignUp $signUp) : bool
    {
        return $this->waiting_list_sign_ups->contains('id', $signUp->id);
    }

    /**
     * The position of a sign up on the waiting list, starting at 1
     */
    public function waitingListPosition(SignUp $signUp) : ?int
    {
        $index = $this->waiting_list_sign_ups->search(
            fn (SignUp $other) : bool => $other->id === $signUp->id
        );

        return $index === false ? null : $index + 1;
    }

    public function memberCanSignUp(LegacyMember $member) : bool
    {
        $deadlineAt = $this->registration_deadline;

        if ($deadlineAt->isPast()) {
            return false;
        }

        if ($this->signUpSettings === null) {
            return false;
        }

        // Once the activity is full, members can still sign up, but they go on the waiting list
        return ! $this->signUps->pluck('member_id')->contains($member->id);
    }

    public function signUp(
        LegacyMember $member,
        int $plusOnes = 0,
        string $dietaryWishes = '',
        bool $hasDriversLicense = false
    ) : SignUp {
        Assert::true($this->memberCanSignUp($member));

        $signUp = new SignUp([
            'member_id' => $member->id,
            'plus_ones' => $plusOnes,
            'status' => $this->statusForNewSignUp(1 + $plusOnes),
            'dietary_wishes' => $dietaryWishes,
            'has_drivers_license' => $hasDriversLicense,
            'notes' => ''
        ]);
        $this->signUps()->save($signUp);
        $this->unsetRelation('signUps');

        return $signUp;
    }

    /**
     * Whether a new sign up of the given number of people gets a place or goes on the waiting list
     */
    public function statusForNewSignUp(int $people) : string
    {
        $placesLeft = $this->places_left;

        if ($placesLeft === null) {
            return SignUp::STATUS_PLACED;
        }

        if ($this->waiting_list_sign_ups->isNotEmpty() || $people > $placesLeft) {
            return SignUp::STATUS_WAITING;
        }

        return SignUp::STATUS_PLACED;
    }

    /**
     * Removes a sign up (a member cancelling, or an admin removing them),
     * and offers the places that free up to the waiting list
     */
    public function cancelSignUp(SignUp $signUp) : void
    {
        Assert::same($signUp->activity_id, $this->id);

        $signUp->delete();
        $this->offerFreePlaces();
    }

    /**
     * Offers the places nobody has taken to the first ones on the waiting list.
     * They still have to accept it themselves, or an admin has to, before it's official.
     * The waiting list is a queue: we stop at the first sign up that doesn't fit,
     * so that a large group isn't skipped by everyone after them.
     *
     * @return Collection<int, SignUp> the sign ups that were offered a place
     */
    public function offerFreePlaces() : Collection
    {
        $this->load('signUps', 'signUpSettings');

        $offered = collect();
        $placesLeft = $this->places_left;

        foreach ($this->waiting_list_sign_ups as $signUp) {
            if ($placesLeft !== null && $signUp->people > $placesLeft) {
                break;
            }

            $signUp->update(['status' => SignUp::STATUS_OFFERED]);
            $offered->push($signUp);

            if ($placesLeft !== null) {
                $placesLeft -= $signUp->people;
            }
        }

        $this->unsetRelation('signUps');

        return $offered;
    }

    /**
     * Makes an offered place official, either because the member accepted it or because an admin did
     */
    public function acceptOfferedPlace(SignUp $signUp) : void
    {
        Assert::same($signUp->activity_id, $this->id);
        Assert::true($signUp->is_offered);

        $signUp->update(['status' => SignUp::STATUS_PLACED]);
        $this->unsetRelation('signUps');
    }

    /**
     * Removes a member that has a place and gives their place to someone from the waiting list.
     * Since an admin does this, the place of the replacement is official right away.
     */
    public function replaceSignUp(SignUp $signUp, SignUp $replacement) : void
    {
        Assert::same($signUp->activity_id, $this->id);
        Assert::same($replacement->activity_id, $this->id);
        Assert::true($signUp->has_place);
        Assert::true($replacement->is_waiting);

        $signUp->delete();
        $replacement->update(['status' => SignUp::STATUS_PLACED]);

        // The replacement may bring fewer people, which leaves places for the waiting list
        $this->offerFreePlaces();
    }
}
