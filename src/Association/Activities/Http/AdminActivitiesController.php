<?php

declare(strict_types=1);

namespace Francken\Association\Activities\Http;

use Francken\Association\Activities\Activity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class AdminActivitiesController
{
    /**
     * The upcoming activities, the soonest first, above the past activities, the most recent
     * first, optionally only those whose name contains the search
     */
    public function index(Request $request) : View
    {
        $search = $request->string('search')->trim()->toString();
        $now = now();

        $query = fn () => Activity::query()
            ->with(['signUpSettings', 'signUps'])
            ->when($search !== '', fn (Builder $query) => $query->where('name', 'like', '%' . $search . '%'));

        $upcomingActivities = $query()
            ->where('start_date', '>=', $now)
            ->orderBy('start_date', 'asc')
            ->get();

        $pastActivities = $query()
            ->where('start_date', '<', $now)
            ->orderBy('start_date', 'desc')
            ->paginate(30)
            ->appends($request->only('search'));

        return view('admin.association.activities.index')
            ->with([
                'upcomingActivities' => $upcomingActivities,
                'pastActivities' => $pastActivities,
                'search' => $search,
            ]);
    }

    public function show(Activity $activity) : View
    {
        return view('admin.association.activities.show')
            ->with([
                'activity' => $activity,
                'breadcrumbs' => [
                    ['url' => action([self::class, 'index']), 'text' => 'Activities'],
                    ['url' => action([static::class, 'show'], ['activity' => $activity]), 'text' => $activity->name],
                ]
            ]);
    }

    public function create() : View
    {
        return view('admin.association.activities.create')
            ->with([
                'activity' => new Activity(),
                'breadcrumbs' => [
                    ['url' => action([self::class, 'index']), 'text' => 'Activities'],
                    ['url' => action([static::class, 'create']), 'text' => 'Create'],
                ]
            ]);
    }

    public function edit(Activity $activity) : View
    {
        return view('admin.association.activities.edit')
            ->with([
                'activity' => $activity,
                'breadcrumbs' => [
                    ['url' => action([self::class, 'index']), 'text' => 'Activities'],
                    ['url' => action([static::class, 'show'], ['activity' => $activity]), 'text' => $activity->name],
                    ['url' => action([static::class, 'edit'], ['activity' => $activity]), 'text' => 'Edit'],
                ]
            ]);
    }

    public function update(Activity $activity) : RedirectResponse
    {
        return redirect()->action([self::class, 'show'], ['activity' => $activity]);
    }

    public function destroy(Activity $activity) : RedirectResponse
    {
        $activity->delete();

        return redirect()->action([self::class, 'index']);
    }
}
