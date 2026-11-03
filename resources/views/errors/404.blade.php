@extends('layout.one-column-layout')

@section('page-title')
    Page not found
@endsection

@section('title', "404 - Page not found - T.F.V. ‘Professor Francken’")

@section('content')
    {{-- A simulation of colliding particles (public/js/404.js), on navy like the page hero --}}
    <div class="not-found">
        <canvas id="mpcCanvas" class="not-found__canvas" width="1600" height="680" aria-hidden="true"></canvas>
    </div>

    <div class="not-found__text">
        <h2 class="home-heading">This page has diffused</h2>
        <p>
            The page you are looking for does not exist (anymore), or it has moved.
            Maybe one of these pages can help you further.
        </p>
        <div class="not-found__links">
            <a class="btn home-btn home-btn--crimson" href="/">Homepage</a>
            <a class="home-link" href="/association/activities">Activities <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
            <a class="home-link" href="/contact">Contact <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
        </div>
    </div>

    <script src="/js/404.js?v={{ filemtime(public_path('js/404.js')) }}"></script>
@endsection
