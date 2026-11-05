@extends('layout.two-column-layout')

@section('page-title')
    {{ $activity->name }}
@endsection
@section('title', $activity->name . " - T.F.V. ‘Professor Francken’")

@section('content')
    <article class="activity-page">
        <div class="activity-page__content">
            @if (trim(strip_tags($activity->compiled_content ?? '')) !== '')
                {!! $activity->compiled_content !!}
            @else
                <p class="activity-page__no-description">
                    No description has been given for this activity yet.
                    See the details of this activity for when and where it takes place.
                </p>
            @endif
        </div>

        @auth
            @php
                $ownSignUp = $activity->signUps->firstWhere('member_id', auth()->user()->member_id);
            @endphp
            @if ($ownSignUp !== null && $ownSignUp->is_offered)
                @include('association.activities.sign-ups._offered', ['signUp' => $ownSignUp])
            @endif

            @can('create', [\Francken\Association\Activities\SignUp::class, $activity])
            @if ($activity->signUpSettings !== null)
            <div class="activity-page__sign-up">
                @include('association.activities.sign-ups._create')
            </div>
            @endif
            @endcan
        @else
            @if ($activity->signUpSettings !== null)
                <div class="activity-page__login">
                    <i class="fas fa-user-plus" aria-hidden="true"></i>
                    <span>
                        @if ($activity->totalSignUps === 0)
                            <a href="{{ action([\Francken\Auth\Http\Controllers\LoginController::class, 'showLoginForm']) }}">Log in</a>
                            and be the first to sign up for this activity!
                        @elseif ($activity->is_full)
                            This activity is full.
                            <a href="{{ action([\Francken\Auth\Http\Controllers\LoginController::class, 'showLoginForm']) }}">Log in</a>
                            to sign up for the waiting list.
                        @else
                            {{ $activity->totalSignUps }} Francken members are joining this activity.
                            <a href="{{ action([\Francken\Auth\Http\Controllers\LoginController::class, 'showLoginForm']) }}">Log in</a>
                            and sign up as well.
                        @endif
                    </span>
                </div>
            @endif
        @endauth
    </article>

    @include('association.activities.comments._index')
@endsection

@section('aside')
    <a class="home-link d-inline-block mb-3" href="{{ action([\Francken\Association\Activities\Http\ActivitiesController::class, 'index']) }}">
        <i class="fas fa-arrow-left" aria-hidden="true"></i>
        All activities
    </a>

    @include('association.activities._facts')

    @if ($activity->signUpSettings !== null)
        <div class="activity-attendees">
            <h2 class="activity-attendees__title">
                Attendees
                <span class="activity-attendees__count">
                    {{ $activity->totalSignUps }} / {{ $activity->signUpSettings->max_sign_ups ?? '∞' }}
                </span>
            </h2>

            @auth
                @include('association.activities._attendees-list', ['signUps' => $activity->placed_sign_ups])

                @if ($activity->waiting_list_sign_ups->isNotEmpty())
                    <h3 class="activity-attendees__subtitle">
                        Waiting list
                        <span class="activity-attendees__count activity-attendees__count--muted">
                            {{ $activity->waiting_list_sign_ups->count() }}
                        </span>
                    </h3>
                    <p class="activity-attendees__help">
                        When someone cancels, the first ones on the waiting list get their place.
                    </p>
                    @include('association.activities._attendees-list', ['signUps' => $activity->waiting_list_sign_ups, 'numbered' => true])
                @endif
            @else
                <p class="mb-0">Log in to see who has signed up.</p>
            @endauth
        </div>
    @endif
@endsection
