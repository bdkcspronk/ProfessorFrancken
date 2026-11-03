@extends('layout.one-column-layout')

@section('page-title')
    Photos
@endsection

@section('title', "Photos - T.F.V. ‘Professor Francken’")

@section('content')
    @if (session('private-album-login') === true)
        <div class="album-notice" role="status">
            <i class="fas fa-unlock" aria-hidden="true"></i>
            You can now see all albums.
        </div>
    @endif

    <div class="page-intro">
        <p>
            The photos of our activities, from borrels and excursions to the members weekend, mostly taken by the <a href="/association/committees/fotocie">Fotocie</a>.
            Would you rather not be in a photo? Let the Fotocie know at <a href="mailto:Fotociefrancken@gmail.com">Fotociefrancken@gmail.com</a>, or the board at <a href="mailto:board@professorfrancken.nl">board@professorfrancken.nl</a>, and we will remove it.
        </p>
    </div>

    @php
        // The newest album is shown over the whole width, like on the old website
        $latest = $albums->onFirstPage() ? $albums->first() : null;
        $others = collect($albums->items())->when($latest !== null, fn ($albums) => $albums->slice(1));
    @endphp

    @if ($latest !== null)
        <div class="album-latest">
            @include('association.photos._album', ['album' => $latest])
        </div>
    @endif

    {{-- The other albums, the newest first, with a heading for each earlier year --}}
    @foreach ($others->groupBy(fn ($album) => ($album->published_at ?? $album->activity_date)?->format('Y')) as $year => $albumsOfYear)
        @if ((string) $year !== now()->format('Y'))
            <h2 class="home-heading album-year">{{ $year }}</h2>
        @endif
        <div class="album-grid">
            @foreach ($albumsOfYear as $album)
                @include('association.photos._album', ['album' => $album])
            @endforeach
        </div>
    @endforeach

    {{ $albums->onEachSide(3)->links() }}

    @if (! $albums->hasMorePages())
        @cannot('view-members-only-albums')
            <div class="login-prompt album-login">
                <h3 class="login-prompt__title"><i class="fas fa-lock" aria-hidden="true"></i> Looking for older albums?</h3>
                <p>Albums older than a year can only be seen by members. <a href="{{ route('login') }}">Log in with your account</a>, or fill in the <strong>album password</strong>. Don't know it? Ask a board member.</p>
                @include('association.photos._login-form')
            </div>
        @endcannot
    @endif
@endsection
