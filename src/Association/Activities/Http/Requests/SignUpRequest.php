<?php

declare(strict_types=1);

namespace Francken\Association\Activities\Http\Requests;

use Francken\Association\Activities\Activity;
use Francken\Association\Activities\SignUp;
use Illuminate\Foundation\Http\FormRequest;
use Webmozart\Assert\Assert;

class SignUpRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules() : array
    {
        return [
            'plus_ones' => $this->plusOneRule(),
            'dietary_wishes' => ['nullable', 'min:1'],
            'has_drivers_license' => ['nullable', 'boolean'],
        ];
    }

    public function plusOnes() : int
    {
        return (int) $this->input('plus_ones');
    }

    public function dietaryWishes() : string
    {
        return $this->input('dietary_wishes') ?? '';
    }

    public function hasDriversLicense() : bool
    {
        return (bool)$this->input('has_drivers_license', false);
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages() : array
    {
        return [
            'plus_ones.max' => "You can't bring more than :max people",
            'plus_ones.min' => "You can't bring fewer than 0 people",
        ];
    }

    private function plusOneRule() : array
    {
        $activity = $this->route('activity');
        Assert::isInstanceOf($activity, Activity::class);
        Assert::notNull($activity->signUpSettings);

        $maxPlusOnes = $activity->signUpSettings->max_plus_ones_per_member;

        $maxSignUps = $activity->signUpSettings->max_sign_ups;
        if ($maxSignUps !== null) {
            // Make sure a member can't have more plus ones than the max total sign ups
            $placesLeft = (int)$maxSignUps - $activity->total_sign_ups - 1;

            // make sure the current sign up is not counted in the max
            $signUp = $this->route('sign_up');
            if ($signUp instanceof SignUp) {
                $placesLeft += 1 + (int)$signUp->plus_ones;
            }

            $maxPlusOnes = $maxPlusOnes === null ? $placesLeft : min($maxPlusOnes, $placesLeft);
        }

        return $maxPlusOnes === null
            ? ['nullable', 'integer', 'min:0']
            : ['nullable', 'integer', 'min:0', 'max:' . max(0, $maxPlusOnes)];
    }
}
