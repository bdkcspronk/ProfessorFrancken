<!doctype html>
<html lang="en" class="home-html">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        @include('layout._seo')

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap">

        @unless(config('env') == 'testing')
            <link rel="stylesheet" href="{{ mix('/dist/css/app.css') }}">
        @endunless

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />

        @include('layout._favicon')
    </head>
    <body class="home">
        <main>
            @include('layout._header')
            @yield('main-content')
            @include('layout._footer')
        </main>

        @include('layout._login-modal')

        @if (Auth::guest())
            <script src="/js/LoginModal.js"></script>
        @endif
        {{-- The modification time makes browsers load the script again after it changed --}}
        <script src="/js/FccLattice.js?v={{ filemtime(public_path('js/FccLattice.js')) }}"></script>
        <script src="/js/LogoBalls.js?v={{ filemtime(public_path('js/LogoBalls.js')) }}"></script>
        <script src="/js/ExternalLinks.js?v={{ filemtime(public_path('js/ExternalLinks.js')) }}"></script>
    </body>
</html>
