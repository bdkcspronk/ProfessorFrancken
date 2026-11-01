{{--
    The calendar of the activities: an academic year (August until July) with the months
    as tiles. The month that is shown is crimson, months with activities are navy
--}}
@php
    $selectedIndex = collect($visibleYears)->search(fn (array $year) : bool => $year['isSelected']);
    $previousYear = $visibleYears[$selectedIndex - 1] ?? null;
    $nextYear = $visibleYears[$selectedIndex + 1] ?? null;
    $shownYear = $visibleYears[$selectedIndex];
@endphp
<div class="activity-calendar">
    <h2 class="home-heading">Calendar</h2>

    <div class="activity-calendar__years">
        @if ($previousYear)
            <a class="activity-calendar__arrow" href="/association/activities/{{ $previousYear['year'] }}/{{ $selectedMonth }}" aria-label="Academic year {{ $previousYear['label'] }}">
                <i class="fas fa-chevron-left" aria-hidden="true"></i>
            </a>
        @else
            <span class="activity-calendar__arrow is-disabled"><i class="fas fa-chevron-left" aria-hidden="true"></i></span>
        @endif

        <span class="activity-calendar__year">{{ $shownYear['label'] }}</span>

        @if ($nextYear)
            <a class="activity-calendar__arrow" href="/association/activities/{{ $nextYear['year'] }}/{{ $selectedMonth }}" aria-label="Academic year {{ $nextYear['label'] }}">
                <i class="fas fa-chevron-right" aria-hidden="true"></i>
            </a>
        @else
            <span class="activity-calendar__arrow is-disabled"><i class="fas fa-chevron-right" aria-hidden="true"></i></span>
        @endif
    </div>

    <div class="activity-calendar__months">
        @foreach ($months as $month)
            @php
                $key = $month['year'] . '-' . $month['number'];
                $count = $activitiesPerMonth[$key] ?? 0;
                $isSelected = ($searchTimeRange ?? false) && (int) $month['number'] === (int) $selectedMonth && $month['year'] === (int) $selectedYear;
            @endphp
            <a
                href="/association/activities/{{ $month['year'] }}/{{ $month['number'] }}"
                @class([
                    'activity-calendar__month',
                    'is-selected' => $isSelected,
                    'is-current' => $key === $currentMonth,
                    'is-empty' => $count === 0,
                ])
                title="{{ $count === 1 ? '1 activity' : $count . ' activities' }}"
            >
                {{ substr($month['name'], 0, 3) }}
                @if ($count > 0)
                    <span class="activity-calendar__count">{{ $count }}</span>
                @endif
            </a>
        @endforeach
    </div>

    <a class="home-link activity-calendar__ical" href="{{ action([\Francken\Association\Activities\Http\IcalController::class, 'index']) }}">
        <i class="far fa-calendar-plus" aria-hidden="true"></i>
        Add to your calendar
    </a>
</div>
