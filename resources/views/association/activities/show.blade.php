@extends('layout.two-column-layout')

@section('page-title')
    {{ $activity->name }}
@endsection
@section('title', $activity->name . " - T.F.V. ‘Professor Francken’")

@section('content')
    <article class="activity-page">
        <div class="activity-page__content">
            {!! $activity->compiled_content !!}
        </div>

        @auth
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
                <ul class="activity-attendees__list">
                    @foreach ($activity->signUps as $signUp)
                        <li>
                            <span>
                                <i class="fas fa-user" aria-hidden="true"></i>
                                {{ $signUp->member->fullname }}
                                @if ($signUp->plus_ones > 0)
                                    (+{{ $signUp->plus_ones }})
                                @endif
                            </span>
                            @can('update', $signUp)
                                <a class="home-link" href="{{ action([\Francken\Association\Activities\Http\SignUpsController::class, 'edit'], ['activity' => $activity, 'sign_up' => $signUp]) }}">
                                    <i class="fas fa-edit" aria-hidden="true"></i> Edit
                                </a>
                            @endcan
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="mb-0">Log in to see who has signed up.</p>
            @endauth
        </div>
    @endif
@endsection
