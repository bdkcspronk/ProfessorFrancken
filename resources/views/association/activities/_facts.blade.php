{{-- The date, time, place, costs and deadline of an activity --}}
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
