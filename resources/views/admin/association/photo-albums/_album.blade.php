{{--
    A card with the cover of an album, like on the photos page of the website, with its
    visibility. Used for the albums of Nextcloud and the old ones of Flickr
--}}
@php
    $cover = $album->coverPhoto ?? $album->photos()->first();
    $visibilities = [
        'public' => ['icon' => 'fas fa-eye', 'text' => 'Public'],
        'members-only' => ['icon' => 'fas fa-user-friends', 'text' => 'Members only'],
        'private' => ['icon' => 'fas fa-eye-slash', 'text' => 'Private'],
    ];
    $visibility = isset($album->visibility) ? ($visibilities[$album->visibility] ?? null) : null;
@endphp
<a class="album-card" href="{{ $href }}" @if ($external ?? false) target="_blank" rel="noopener" @endif>
    @if ($cover !== null && $cover->src() !== '')
        <img class="album-card__cover" src="{{ $cover->src() }}" alt="" loading="lazy">
    @else
        <span class="album-card__cover album-card__cover--empty" aria-hidden="true">
            <i class="fas fa-camera"></i>
        </span>
    @endif

    @if ($visibility !== null || ($external ?? false))
        <span class="album-card__badge">
            @if ($external ?? false)
                <i class="fab fa-flickr" aria-hidden="true"></i> Flickr
            @else
                <i class="{{ $visibility['icon'] }}" aria-hidden="true"></i> {{ $visibility['text'] }}
            @endif
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
