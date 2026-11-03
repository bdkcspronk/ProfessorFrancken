@extends('pages.association')

@section('page-title')
    Francken Vrij
@endsection
@section('header-image-url', '/images/header/library-books.jpeg')
@section('main-content')
<div class="container my-4">
    <div class="page-intro">
        <p>Francken Vrij is the popular science magazine of the Technisch Fysische Vereniging ‘Professor Francken’ and is distributed to its members, sponsors and other interested parties. The complete Francken Vrij archive can be found on this page.</p>
        <p>The theme of each edition is different but relates to (Applied) Physics. The contents of the magazine change slightly from edition to edition but generally one might expect an article by one of the applied physics research groups, an article by a student about his or her internship or a graduate about her or his new job. Most editions come with a challenging puzzle and a fun comic as well.</p>
    </div>

    <h2 class="home-heading">The latest volume</h2>
    <div class="row mb-5">
        @foreach($latestEditions as $edition)
            <div class="col-sm-4 mb-4">
                <article class="fv-edition fv-edition--latest">
                    <a class="fv-edition__cover" href="{{ $edition->pdf() }}">
                        <img src="{{ $edition->cover() }}" alt="Cover of {{ $edition->title() }}">
                    </a>
                    <h3 class="fv-edition__title">{{ $edition->title() }}</h3>
                    <a href="{{ $edition->pdf() }}" class="btn home-btn home-btn--crimson">
                        <i class="fas fa-download" aria-hidden="true"></i>
                        Download
                    </a>
                </article>
            </div>
        @endforeach
    </div>

    @if (count($volumes) > 0)
        <h2 class="home-heading">Archive</h2>
        @foreach($volumes as $volume)
            <section class="fv-volume">
                <h3 class="fv-volume__title">Volume {{ $volume->volume() }}</h3>
                <div class="fv-volume__editions">
                    @foreach($volume->editions() as $edition)
                        <a class="fv-edition" href="{{ $edition->pdf() }}">
                            <span class="fv-edition__cover">
                                <img src="{{ $edition->cover() }}" alt="Cover of {{ $edition->title() }}">
                            </span>
                            <span class="fv-edition__title">{{ $edition->title() }}</span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endforeach
    @endif

    @guest
        <div class="mt-5">
            <x-login-prompt title="Login to view older editions">
            </x-login-prompt>
        </div>
    @endguest
</div>
@endsection
