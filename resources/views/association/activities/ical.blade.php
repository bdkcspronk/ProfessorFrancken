@extends('layout.one-column-layout')

@section('page-title')
    Synchronize our calendar
@endsection
@section('title', 'Synchronize our calendar')
@section('description', "Synchronize the calendar of T.F.V. ‘Professor Francken’ with your own online agenda.")

@section('content')
    <div class="committee-page cms-page">
        <p>
            Synchronize our calendar by importing its url into your preferred external calendar.
        </p>

        {{-- The url of the calendar, with a button that copies it --}}
        <div class="ical-url">
            <code id="calendar-url" class="ical-url__value">{{ $calendarUrl }}</code>
            <button id="copy-calendar-url" class="btn home-btn home-btn--crimson ical-url__copy" type="button" data-copy-calendar-url>
                <i class="far fa-copy" aria-hidden="true"></i>
                <span data-copy-label>Copy</span>
            </button>
        </div>

        <h2>Google Calendar</h2>
        <p>
            To import our calendar into your Google Calendar account, go to <strong>Settings › Add calendar › From URL</strong> and paste the url from above.
        </p>

        <h2>Outlook Calendar</h2>
        <p>
            To add our calendar on Outlook, on the top menu bar, click <strong>Add calendar</strong>.
            Then click <strong>From Internet</strong>, paste the URL and name your calendar.
            Click save. That’s it!
        </p>
    </div>
@endsection

@push('scripts')
<script>
    // Copies the url of the calendar and shows for a moment that it did
    (function () {
        var button = document.querySelector('[data-copy-calendar-url]');
        if (!button || !navigator.clipboard) {
            return;
        }
        var label = button.querySelector('[data-copy-label]');
        var url = document.getElementById('calendar-url').textContent.trim();
        var timeout = null;

        button.addEventListener('click', function () {
            navigator.clipboard.writeText(url).then(function () {
                label.textContent = 'Copied';
                clearTimeout(timeout);
                timeout = setTimeout(function () {
                    label.textContent = 'Copy';
                }, 2000);
            });
        });
    })();
</script>
@endpush
