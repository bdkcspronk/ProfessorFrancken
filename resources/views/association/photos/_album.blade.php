{{-- A card with the cover of an album, used by both the Nextcloud and the old Flickr albums --}}
@php
    $cover = $album->coverPhoto ?? $album->photos()->first();
    $date = $album->published_at ?? $album->activity_date;
    $amount = $album->amount_of_photos ?? $album->photos->count();
@endphp
<a class="album-card" href="{{ $album->url() }}">
    @if ($cover !== null && $cover->src() !== '')
        <img class="album-card__cover" src="{{ $cover->src() }}" alt="" loading="lazy">
    @else
        <span class="album-card__cover album-card__cover--empty" aria-hidden="true">
            <i class="fas fa-camera"></i>
        </span>
    @endif
    <span class="album-card__caption">
        @if ($date !== null)
            <span class="album-card__date">{{ $date->format('j F Y') }}</span>
        @endif
        <span class="album-card__title">{{ $album->title }}</span>
        <span class="album-card__amount">
            <i class="fas fa-images" aria-hidden="true"></i>
            {{ $amount }} {{ Str::plural('photo', $amount) }}
        </span>
    </span>
</a>
