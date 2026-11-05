@extends('admin.layout')
@section('page-title', 'Photo albums')

@section('content')
    {{-- A tab for every year that has albums, the newest first --}}
    @if ($years->isNotEmpty())
        <nav class="album-years" aria-label="Years">
            @foreach ($years as $albumYear)
                <a
                    href="{{ action([\Francken\Association\Photos\Http\Controllers\AdminPhotoAlbumsController::class, 'index'], ['year' => $albumYear]) }}"
                    @class(['album-years__year', 'is-active' => $albumYear === $year])
                    @if ($albumYear === $year) aria-current="page" @endif
                >{{ $albumYear }}</a>
            @endforeach
        </nav>
    @endif

    <h2 class="home-heading album-year">
        {{ $year }}
        <small class="album-year__count">
            {{ $albums->count() + $flickrAlbums->count() }} {{ Str::plural('album', $albums->count() + $flickrAlbums->count()) }}
        </small>
    </h2>

    @if ($albums->isEmpty() && $flickrAlbums->isEmpty())
        <p class="text-muted">There are no albums of {{ $year }} yet.</p>
    @else
        {{-- The albums of the year, the newest first --}}
        <div class="album-grid album-grid--admin">
            @foreach ($albums as $album)
                @include('admin.association.photo-albums._album', [
                    'album' => $album,
                    'href' => action([\Francken\Association\Photos\Http\Controllers\AdminPhotoAlbumsController::class, 'show'], ['album' => $album]),
                    'date' => $album->published_at,
                    'amount' => $album->photos_count,
                ])
            @endforeach

            @foreach ($flickrAlbums as $album)
                @include('admin.association.photo-albums._album', [
                    'album' => $album,
                    'href' => $album->url(),
                    'date' => $album->activity_date,
                    'amount' => $album->amount_of_photos,
                    'external' => true,
                ])
            @endforeach
        </div>
    @endif
@endsection

@section('actions')
    <a href="{{ action([\Francken\Association\Photos\Http\Controllers\AdminPhotoAlbumsController::class, 'create'], ['year' => $year]) }}"
        class="btn btn-primary"
    >
        <i class="fas fa-plus"></i>
        Create album
    </a>
    {!! Form::open(['url' => action([\Francken\Association\Photos\Http\Controllers\AdminPhotoAlbumsController::class, 'refresh'])]) !!}
    <button class='btn btn-primary'>
        <i class="fas fa-sync"></i>
        Refresh
    </button>
    {!! Form::close() !!}
@endsection
