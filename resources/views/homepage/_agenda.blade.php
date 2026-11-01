<section class="container">
    <div class="home-agenda">
        <div class="home-agenda__header">
            <h2 class="home-heading">Agenda</h2>
            <a class="home-link" href="{{ action([\Francken\Association\Activities\Http\IcalController::class, 'index']) }}">
                <i class="far fa-calendar-plus" aria-hidden="true"></i>
                Add to your calendar
            </a>
        </div>

        {{-- Phones only show the first four activities, so the agenda doesn't get too long --}}
        @php($position = 0)
        @forelse ($agenda as $title => $activities)
            <h3 @class(['home-agenda__group', 'd-none d-sm-flex' => $position >= 4])>{{ $title }}</h3>
            <div class="row">
                @foreach ($activities as $activity)
                    <div @class(['col-sm-6 col-lg-3 mb-3', 'd-none d-sm-block' => $position++ >= 4])>
                        @include('homepage._agenda-item', ['activity' => $activity])
                    </div>
                @endforeach
            </div>
        @empty
            <p class="mb-0">
                There are no activities planned at the moment, keep an eye on our
                <a href="/association/activities">activities page</a>.
            </p>
        @endforelse
    </div>
</section>
