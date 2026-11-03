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

    <div class="activity-facts">
        <div class="activity-facts__date">
            <span class="activity-facts__day">{{ $activity->start_date->format('d') }}</span>
            <span class="activity-facts__month">{{ $activity->start_date->format('M Y') }}</span>
        </div>

        <dl class="activity-facts__list">
            <dt><i class="far fa-clock fa-fw" aria-hidden="true"></i> When</dt>
            <dd>
                {{ $activity->start_date->format('l j F, H:i') }}
                –
                @if ($activity->end_date->isSameDay($activity->start_date))
                    {{ $activity->end_date->format('H:i') }}
                @else
                    {{ $activity->end_date->format('l j F, H:i') }}
                @endif
            </dd>

            @if (($activity->location ?? '') !== '')
                <dt><i class="fas fa-map-marker-alt fa-fw" aria-hidden="true"></i> Where</dt>
                <dd>{{ $activity->location }}</dd>
            @endif

            @if ($activity->signUpSettings !== null)
                <dt><i class="fas fa-euro-sign fa-fw" aria-hidden="true"></i> Costs</dt>
                <dd>
                    @if ($activity->signUpSettings->is_free)
                        Free
                    @else
                        €{{ number_format($activity->signUpSettings->costs_per_person / 100, 2, ',', '') }} per person
                    @endif
                </dd>

                <dt><i class="fas fa-hourglass-half fa-fw" aria-hidden="true"></i> Sign up before</dt>
                <dd>{{ $activity->signUpSettings->deadline_at->format('l j F, H:i') }}</dd>
            @endif
        </dl>
    </div>

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
