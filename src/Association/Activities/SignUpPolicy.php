<?php

declare(strict_types=1);

namespace Francken\Association\Activities;

use Francken\Auth\Account;
use Illuminate\Auth\Access\HandlesAuthorization;

class SignUpPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     */
    public function create(Account $account, Activity $activity) : bool
    {
        if ($account->member === null) {
            return false;
        }

        return $activity->memberCanSignUp($account->member);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Account $account, SignUp $signUp) : bool
    {
        if ($signUp->activity === null) {
            return false;
        }

        $deadlineAt = $signUp->activity->registration_deadline;

        if ($deadlineAt->isPast()) {
            return false;
        }

        return $signUp->member_id === $account->member_id;
    }

    /**
     * Determine whether the user can accept the place that has been offered to them.
     * This is possible after the deadline as well, since a place can open up at any time.
     */
    public function accept(Account $account, SignUp $signUp) : bool
    {
        return $signUp->is_offered && $signUp->member_id === $account->member_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Account $account, SignUp $signUp) : bool
    {
        return $signUp->member_id === $account->member_id;
    }
}
