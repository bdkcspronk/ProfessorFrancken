<?php

declare(strict_types=1);

namespace Francken\Association\Activities\Http;

use Francken\Association\Activities\Activity;
use Francken\Association\Activities\Http\Requests\SignUpRequest;
use Francken\Association\Activities\SignUp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class SignUpsController
{
    public function store(SignUpRequest $request, Activity $activity) : RedirectResponse
    {
        $activity->signUp(
            $request->user()->member,
            $request->plusOnes(),
            $request->dietaryWishes(),
            $request->hasDriversLicense()
        );

        return redirect()->action([ActivitiesController::class, 'show'], ['activity' => $activity]);
    }

    public function edit(Request $request, Activity $activity, SignUp $signUp) : View
    {
        $account = $request->user();

        return view('association.activities.sign-ups.edit', [
            'activity' => $activity,
            'signUp' => $signUp,
            'account' => $account,
            'searchTimeRange' => false,
            'breadcrumbs' => [
                ['url' => '/association/', 'text' => 'Association'],
                ['url' => action([ActivitiesController::class, 'index']), 'text' => 'Activities'],
                ['url' => action([ActivitiesController::class, 'show'], ['activity' => $activity]), 'text' => $activity->name],
                ['url' => action([self::class, 'edit'], ['activity' => $activity, 'sign_up' => $signUp]), 'text' => optional($signUp->member)->fullname],
            ],
        ]);
    }

    public function update(SignUpRequest $request, Activity $activity, SignUp $signUp) : RedirectResponse
    {
        $signUp->update([
            'plus_ones' => $request->plusOnes(),
            'dietary_wishes' => $request->dietaryWishes(),
            'has_drivers_license' => $request->hasDriversLicense(),
        ]);

        // Bringing fewer people may leave places for the waiting list
        $activity->offerFreePlaces();

        return redirect()->action([ActivitiesController::class, 'show'], ['activity' => $activity]);
    }

    /**
     * The member accepts the place that opened up for them on the waiting list
     */
    public function accept(Activity $activity, SignUp $signUp) : RedirectResponse
    {
        $activity->acceptOfferedPlace($signUp);

        return redirect()->action([ActivitiesController::class, 'show'], ['activity' => $activity]);
    }

    /**
     * Cancelling a sign up, which is also how a member declines a place offered to them
     */
    public function destroy(Activity $activity, SignUp $signUp) : RedirectResponse
    {
        $activity->cancelSignUp($signUp);

        return redirect()->action([ActivitiesController::class, 'show'], ['activity' => $activity]);
    }
}
