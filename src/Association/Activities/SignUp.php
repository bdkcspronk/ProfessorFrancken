<?php

declare(strict_types=1);

namespace Francken\Association\Activities;

use Francken\Association\LegacyMember;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webmozart\Assert\Assert;

final class SignUp extends Model
{
    /** The member has a place */
    public const STATUS_PLACED = 'placed';

    /** A place opened up for this member, which they or an admin still have to accept */
    public const STATUS_OFFERED = 'offered';

    /** The member is on the waiting list */
    public const STATUS_WAITING = 'waiting';

    /**
     * @var string
     */
    protected $table = 'association_activities_sign_ups';

    /**
     * @var string[]
     */
    protected $fillable = [
        'member_id',
        'plus_ones',
        'status',
        'dietary_wishes',
        'has_drivers_license',
        'discount',
        'notes',
    ];

    protected $casts = [
        'member_id' => 'int',
        'activity_id' => 'int',
        'plus_ones' => 'int',
        'has_drivers_license' => 'boolean',
        'discount' => 'int',
    ];

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => self::STATUS_PLACED,
    ];

    /**
     * The number of people this sign up counts for: the member and their plus ones
     */
    public function getPeopleAttribute() : int
    {
        return 1 + (int)$this->plus_ones;
    }

    public function getIsPlacedAttribute() : bool
    {
        return $this->status === self::STATUS_PLACED;
    }

    public function getIsOfferedAttribute() : bool
    {
        return $this->status === self::STATUS_OFFERED;
    }

    public function getIsWaitingAttribute() : bool
    {
        return $this->status === self::STATUS_WAITING;
    }

    /**
     * Placed and offered sign ups both take up places
     */
    public function getHasPlaceAttribute() : bool
    {
        return ! $this->is_waiting;
    }

    public function member() : BelongsTo
    {
        return $this->belongsTo(LegacyMember::class)->withTrashed();
    }

    public function activity() : BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function getCostsAttribute() : int
    {
        Assert::notNull($this->activity);
        Assert::notNull($this->activity->signUpSettings);

        $activityCostsPerPerson = $this->activity->signUpSettings->costs_per_person;

        return (int)($activityCostsPerPerson * (1 + $this->plus_ones) - $this->discount);
    }

    public function getExportNameAttribute() : string
    {
        Assert::notNull($this->member);

        return collect([
                $this->member->achternaam,
                $this->member->voornaam,
                $this->member->tussenvoegsel
            ])
                ->filter()
                ->implode(' ');
    }
}
