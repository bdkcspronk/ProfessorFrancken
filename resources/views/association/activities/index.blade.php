@extends('layout.two-column-layout')

@section('page-title')
    @if($searchTimeRange)
        Activities in {{ $selectedDate->format('F \'y')}}
    @else
        Upcoming activities
    @endif
@endsection


{{-- pagination in titel! --}}
@if($searchTimeRange)

    @section('title', 'Activities in ' . ($selectedMonth ? $selectedMonth . ' ' : '')  . $selectedYear)
    @section('description', 'Activiteiten in ' . ($selectedMonth ? $selectedMonth . ' ' : '') . $selectedYear)
@else
    @section('title', "Upcoming activities - T.F.V. ‘Professor Francken’")
@endif

@section('content')
    {{-- The activities grouped by month, like the agenda on the homepage --}}
    @forelse ($activities->groupBy(fn ($activity) => $activity->start_date->format('F Y')) as $monthName => $activitiesInMonth)
        <h3 @class(['home-agenda__group', 'mt-0' => $loop->first])>{{ $monthName }}</h3>
        <div class="row">
            @foreach ($activitiesInMonth as $activity)
                <div class="col-md-6 mb-3">
                    @include('association.activities._activity-card', ['activity' => $activity])
                </div>
            @endforeach
        </div>
    @empty
        <div class="activity-calendar">
            <p class="mb-0">
                There are no activities planned in this period.
            </p>
        </div>
    @endforelse
@endsection

@section('aside')
    @include('association.activities._calendar')
@endsection
