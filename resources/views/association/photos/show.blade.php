@extends('layout.one-column-layout')

@section('page-title')
    {{ $album->title }}
@endsection

@section('title', $album->title . " - Photos - T.F.V. ‘Professor Francken’")
@section('description', $album->description)

@section('content')
    @php
        $date = $album->published_at ?? $album->activity_date;
    @endphp
    <div class="page-intro album-intro">
        <a class="home-link d-inline-block mb-2" href="{{ action([\Francken\Association\Photos\Http\Controllers\PhotosController::class, 'index']) }}">
            <i class="fas fa-arrow-left" aria-hidden="true"></i> All albums
        </a>
        @if ($date !== null)
            <p class="album-intro__date">{{ $date->format('l j F Y') }}</p>
        @endif
        @if ($album->description)
            <p>{{ $album->description }}</p>
        @endif
    </div>

    {{-- Square previews in the order they were taken; every photo opens larger in a
         lightbox, where you can go through the whole page --}}
    <ul class="photo-wall" id="album-photos">
        @foreach ($photos as $photo)
            @include('association.photos._photo', ['photo' => $photo])
        @endforeach
    </ul>

    {{ $photos->links() }}

    @if (isset($previous_album) || isset($next_album))
        <h2 class="home-heading album-year">More albums</h2>
        <nav class="news-pager" aria-label="Other albums">
            @isset($previous_album)
                <a class="news-pager__link" href="{{ $previous_album->url() }}">
                    <span class="news-pager__label"><i class="fas fa-arrow-left" aria-hidden="true"></i> Previous album</span>
                    {{ $previous_album->title }}
                </a>
            @endisset

            @isset($next_album)
                <a class="news-pager__link news-pager__link--next" href="{{ $next_album->url() }}">
                    <span class="news-pager__label">Next album <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
                    {{ $next_album->title }}
                </a>
            @endisset
        </nav>
    @endif

    {{-- The photos open in a lightbox, see resources/assets/js/photos.js --}}
    @push('scripts')
        @vite('resources/assets/js/photos.js')
    @endpush
@endsection
