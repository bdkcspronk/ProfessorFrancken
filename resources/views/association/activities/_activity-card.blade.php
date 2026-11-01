{{-- An activity in the style of the agenda on the homepage, with its location and sign up details --}}
<a class="home-activity activity-card" href="{{ action([\Francken\Association\Activities\Http\ActivitiesController::class, 'show'], ['activity' => $activity]) }}">
    <div class="home-activity__date">
        <span class="home-activity__day">{{ $activity->start_date->format('d') }}</span>
        <span class="home-activity__month">{{ $activity->start_date->format('M') }}</span>
    </div>
    <div class="home-activity__body">
        <div class="home-activity__when">
            @if ($activity->start_date->isToday())
                Today
            @elseif ($activity->start_date->isTomorrow())
                Tomorrow
            @else
                {{ $activity->start_date->format('l') }}
            @endif
            · {{ $activity->start_date->format('H:i') }}
        </div>
        <h4 class="home-activity__name">{{ $activity->name }}</h4>

        @if ($activity->location !== '')
            <div class="activity-card__detail">
                <i class="fas fa-map-marker-alt fa-fw" aria-hidden="true"></i>
                {{ $activity->location }}
            </div>
        @endif

        @if ($activity->signUpSettings !== null || $activity->comments_count > 0)
            <div class="activity-card__detail">
                @if ($activity->signUpSettings !== null)
                    <span class="mr-3">
                        <i class="fas fa-euro-sign fa-fw" aria-hidden="true"></i>
                        @if ($activity->signUpSettings->is_free)
                            Free
                        @else
                            {{ number_format($activity->signUpSettings->costs_per_person / 100, 2) }}
                        @endif
                    </span>
                    <span class="mr-3">
                        <i class="fas fa-users fa-fw" aria-hidden="true"></i>
                        {{ $activity->totalSignUps }} / {{ $activity->signUpSettings->max_sign_ups ?? '∞' }}
                    </span>
                @endif
                @if ($activity->comments_count > 0)
                    <span>
                        <i class="fas fa-comments fa-fw" aria-hidden="true"></i>
                        {{ $activity->comments_count }}
                    </span>
                @endif
            </div>
        @endif

        @if ($activity->signUpSettings !== null && $activity->signUpSettings->deadline_at->isFuture())
            <div class="home-activity__sign-up">
                <i class="fas fa-user-plus" aria-hidden="true"></i>
                Sign up before {{ $activity->signUpSettings->deadline_at->format('j M') }}
            </div>
        @endif
    </div>
</a>
