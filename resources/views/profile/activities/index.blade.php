@extends('profile.layout')

@section('content')
    <h2 class="home-heading profile-heading">Activities you signed up for</h2>

    <ul class="list-unstyled">
        @forelse ($activities as $activity)
            @php
            $signUp = $activity->signUps->where('member_id', $member->id)->first();
            @endphp
            <li class="profile-activity">
                <div class="activity-facts__date profile-activity__date">
                    <span class="activity-facts__day">{{ $activity->start_date->format('d') }}</span>
                    <span class="activity-facts__month">{{ $activity->start_date->format('M') }}</span>
                </div>
                <div class="profile-activity__body">
                    <h3 class="profile-activity__name">
                        <a href="{{ action([\Francken\Association\Activities\Http\ActivitiesController::class, 'show'], ['activity' => $activity]) }}">
                            {{ $activity->name }}
                        </a>
                    </h3>
                    <ul class="profile-activity__facts">
                        <li>
                            <i class="fas fa-clock" aria-hidden="true"></i>
                            {{ $activity->schedule }}
                        </li>
                        @if ($activity->location !== '')
                            <li>
                                <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                                {{ $activity->location }}
                            </li>
                        @endif
                        @if ($signUp !== null && $signUp->is_waiting)
                            <li>
                                <i class="fas fa-hourglass-half" aria-hidden="true"></i>
                                Number {{ $activity->waitingListPosition($signUp) }} on the waiting list
                            </li>
                        @elseif ($signUp !== null && $signUp->is_offered)
                            <li class="profile-activity__offer">
                                <i class="fas fa-star" aria-hidden="true"></i>
                                A place opened up for you:
                                <a href="{{ action([\Francken\Association\Activities\Http\ActivitiesController::class, 'show'], ['activity' => $activity]) }}">accept it</a>
                            </li>
                        @endif
                        @if ($activity->signUpSettings !== null && ! $activity->signUpSettings->is_free)
                            <li>
                                <i class="fas fa-euro-sign" aria-hidden="true"></i>
                                {{ number_format($activity->signUpSettings->costs_per_person / 100, 2) }}
                            </li>
                        @endif
                    </ul>
                </div>
                @can('update', $signUp)
                    <a
                        class="profile-panel__edit"
                        href="{{ action(
                                     [\Francken\Association\Activities\Http\SignUpsController::class, 'edit'],
                                     ['activity' => $activity, 'sign_up' => $signUp])
                              }}"
                    >
                        <i class="fas fa-edit" aria-hidden="true"></i>
                        Edit
                    </a>
                @endcan
            </li>
        @empty
            <li class="profile-panel profile-panel--empty">
                <i class="fas fa-calendar-plus" aria-hidden="true"></i>
                <p class="mb-0">
                    You have not signed up for any activities yet.
                    Have a look at the <a href="{{ action([\Francken\Association\Activities\Http\ActivitiesController::class, 'index']) }}">upcoming activities</a> to find something to join.
                </p>
            </li>
        @endforelse
    </ul>

    {!! $activities->links() !!}
@endsection
