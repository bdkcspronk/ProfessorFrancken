<?php

declare(strict_types=1);

namespace Francken\Association\Activities\Http;

use Francken\Association\Activities\Activity;
use Francken\Association\Activities\Http\Requests\AdminSignUpRequest;
use Francken\Association\Activities\SignUp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class AdminSignUpsController
{
    public function create(Activity $activity) : View
    {
        return view('admin.association.activities.sign-ups.create')
            ->with([
                'activity' => $activity,
                'signUp' => new SignUp(),
                'breadcrumbs' => [
                    ['url' => action([AdminActivitiesController::class, 'index']), 'text' => 'Activities'],
                    ['url' => action([AdminActivitiesController::class, 'show'], ['activity' => $activity]), 'text' => $activity->name],
                    ['url' => action([static::class, 'create'], ['activity' => $activity]), 'text' => 'Add sign up'],
                ]
            ]);
    }

    public function store(AdminSignUpRequest $request, Activity $activity) : RedirectResponse
    {
        $activity->signUps()->save(
            new SignUp([
                'member_id' => $request->memberId(),
                'plus_ones' => $request->plusOnes(),
                'status' => $activity->statusForNewSignUp(1 + $request->plusOnes()),
                'discount' => $request->discount(),
                'dietary_wishes' => $request->dietaryWishes(),
                'has_drivers_license' => $request->hasDriversLicense(),
                'notes' => $request->notes()
            ])
        );

        return redirect()->action([AdminActivitiesController::class, 'show'], ['activity' => $activity]);
    }

    public function edit(Activity $activity, SignUp $signUp) : View
    {
        return view('admin.association.activities.sign-ups.edit')
            ->with([
                'activity' => $activity,
                'signUp' => $signUp,
                'breadcrumbs' => [
                    ['url' => action([AdminActivitiesController::class, 'index']), 'text' => 'Activities'],
                    ['url' => action([AdminActivitiesController::class, 'show'], ['activity' => $activity]), 'text' => $activity->name],
                    ['url' => action([static::class, 'edit'], ['activity' => $activity, 'sign_up' => $signUp]), 'text' => 'Edit sign up'],
                ]
            ]);
    }

    public function update(AdminSignUpRequest $request, Activity $activity, SignUp $signUp) : RedirectResponse
    {
        $signUp->update([
            'plus_ones' => $request->plusOnes(),
            'discount' => $request->discount(),
            'dietary_wishes' => $request->dietaryWishes(),
            'has_drivers_license' => $request->hasDriversLicense(),
            'notes' => $request->notes()
        ]);

        // Bringing fewer people may leave places for the waiting list
        $activity->offerFreePlaces();

        return redirect()->action([AdminActivitiesController::class, 'show'], ['activity' => $activity]);
    }

    /**
     * Makes the place that was offered to someone from the waiting list official
     */
    public function approve(Activity $activity, SignUp $signUp) : RedirectResponse
    {
        abort_unless($signUp->activity_id === $activity->id && $signUp->is_offered, 404);

        $activity->acceptOfferedPlace($signUp);

        return redirect()->action([AdminActivitiesController::class, 'show'], ['activity' => $activity]);
    }

    /**
     * Removes a member with a place and gives it to someone from the waiting list
     */
    public function replace(Request $request, Activity $activity, SignUp $signUp) : RedirectResponse
    {
        abort_unless($signUp->activity_id === $activity->id && $signUp->has_place, 404);

        $request->validate(['replacement_id' => ['required', 'integer']]);
        $replacement = $activity->signUps()
            ->where('status', SignUp::STATUS_WAITING)
            ->findOrFail($request->integer('replacement_id'));

        $activity->replaceSignUp($signUp, $replacement);

        return redirect()->action([AdminActivitiesController::class, 'show'], ['activity' => $activity]);
    }

    public function destroy(Activity $activity, SignUp $signUp) : RedirectResponse
    {
        $activity->cancelSignUp($signUp);

        return redirect()->action([AdminActivitiesController::class, 'show'], ['activity' => $activity]);
    }
}
