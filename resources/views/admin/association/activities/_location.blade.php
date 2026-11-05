{{-- The location of the activity with a map; most activities from the calendar have no location,
     for which Google can't show a map --}}
<div class="card mb-4">
    <div class="card-body">
        <h4 class="font-weight-bold">
            <i class="fas fa-map-marker"></i>
            Location
        </h4>

        @if (trim((string) $activity->location) === '')
            <p class="small text-muted mb-0">
                No location set. Add one to the activity in the Google Calendar to show it here with a map.
            </p>
        @else
            <p>
                {{ $activity->location }}
            </p>

            {{-- Without a key for Google Maps in .env the location is shown without a map --}}
            @if ($activity->google_maps_embed_uri !== null)
                <div>
                    <iframe
                        title="Map of {{ $activity->location }}"
                        loading="lazy"
                        style="border:0; width: 100%; height: 400px"
                        src="{{ $activity->google_maps_embed_uri }}" allowfullscreen
                    ></iframe>
                </div>
            @endif
        @endif
    </div>
</div>
