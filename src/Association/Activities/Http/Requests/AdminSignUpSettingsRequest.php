<?php

declare(strict_types=1);

namespace Francken\Association\Activities\Http\Requests;

use DateTimeImmutable;
use Illuminate\Foundation\Http\FormRequest;
use Webmozart\Assert\Assert;

class AdminSignUpSettingsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules() : array
    {
        return [
            'max_sign_ups' => ['nullable', 'min:1'],
            'costs_per_person' => ['nullable', 'numeric', 'min:0'],
            'max_plus_ones_per_member' => ['nullable', 'min:1'],
            'ask_for_dietary_wishes' => ['nullable', 'boolean'],
            'ask_for_drivers_license' => ['nullable', 'boolean'],
            'deadline_at' => ['nullable', 'date_format:Y-m-d H:i:s']
        ];
    }

    public function maxSignUps() : ?int
    {
        if ($this->input('max_sign_ups') === null) {
            return null;
        }

        return (int)$this->input('max_sign_ups');
    }

    /**
     * The costs are filled in in euros (i.e. 5 or 5.50), but stored in cents
     */
    public function costsPerPerson() : ?int
    {
        if ( ! $this->has('costs_per_person')) {
            return null;
        }

        // Round instead of casting, since for example 19.99 * 100 = 1998.9999...
        $euros = $this->input('costs_per_person');
        Assert::nullOrNumeric($euros);

        return (int)round((float)$euros * 100);
    }

    public function maxPlusOnesPerMember() : ?int
    {
        if ($this->input('max_plus_ones_per_member') === null) {
            return null;
        }

        return (int)$this->input('max_plus_ones_per_member');
    }

    public function askForDietaryWishes() : bool
    {
        return (bool)$this->input('ask_for_dietary_wishes', false);
    }

    public function askForDriversLicense() : bool
    {
        return (bool)$this->input('ask_for_drivers_license', false);
    }

    public function deadlineAt() : ?DateTimeImmutable
    {
        if ($this->input('deadline_at') === null) {
            return null;
        }

        $deadlineAt = DateTimeImmutable::createFromFormat(
            'Y-m-d H:i:s',
            $this->input('deadline_at')
        );

        Assert::isInstanceOf($deadlineAt, DateTimeImmutable::class);

        return $deadlineAt;
    }
}
