@extends('admin.layout')
@section('page-title', 'Activities')

@section('content')
    <form class="admin-activities-search mb-4" method="GET" action="{{ action([\Francken\Association\Activities\Http\AdminActivitiesController::class, 'index']) }}" role="search">
        <input type="search" name="search" value="{{ $search }}" class="form-control" placeholder="Search activities by name" aria-label="Search activities by name">
        <button type="submit" class="btn btn-primary"><i class="fas fa-search" aria-hidden="true"></i> Search</button>
        @if ($search !== '')
            <a class="btn btn-text text-primary" href="{{ action([\Francken\Association\Activities\Http\AdminActivitiesController::class, 'index']) }}">
                <i class="fas fa-times" aria-hidden="true"></i> Clear
            </a>
        @endif
    </form>

    @foreach ([
        ['title' => 'Upcoming', 'activities' => $upcomingActivities, 'empty' => 'There are no upcoming activities.'],
        ['title' => 'Past', 'activities' => $pastActivities, 'empty' => 'There are no past activities.'],
    ] as $section)
        <h2 class="h4 admin-activities-heading">
            {{ $section['title'] }}
            @if ($section['activities'] instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                <small class="text-muted">{{ $section['activities']->total() }}</small>
            @else
                <small class="text-muted">{{ $section['activities']->count() }}</small>
            @endif
        </h2>
        <div class="card mb-4">
            <table class="table table-hover admin-clickable-rows mb-0">
                <thead>
                    <tr>
                        <th style="width: 10rem">Date</th>
                        <th>Activity</th>
                        <th class="text-right">Sign-ups</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($section['activities'] as $activity)
                        @include('admin.association.activities._activity-row', ['activity' => $activity])
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">{{ $section['empty'] }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if ($section['activities'] instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $section['activities']->hasPages())
                <div class="card-footer">
                    {{ $section['activities']->links() }}
                </div>
            @endif
        </div>
    @endforeach
@endsection

@section('actions')
    <a href="{{ action([\Francken\Association\Activities\Http\AdminActivitiesController::class, 'create']) }}"
       class="btn btn-primary"
    >
        <i class="fas fa-plus"></i>
        Plan new activity
    </a>
@endsection
