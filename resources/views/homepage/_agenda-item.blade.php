<a class="home-activity" href="{{ action([\Francken\Association\Activities\Http\ActivitiesController::class, 'show'], ['activity' => $activity]) }}">
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
        @if ($activity->signUpSettings !== null && $activity->signUpSettings->deadline_at->isFuture())
            <div class="home-activity__sign-up">
                <i class="fas fa-user-plus" aria-hidden="true"></i>
                Sign up before {{ $activity->signUpSettings->deadline_at->format('j M') }}
            </div>
        @endif
    </div>
</a>
