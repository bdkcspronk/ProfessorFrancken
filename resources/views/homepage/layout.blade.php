<!doctype html>
<html lang="en" class="home-html">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        @include('layout._seo')

        @unless(config('env') == 'testing')
            @vite(['resources/assets/sass/app.scss', 'resources/assets/css/vendor.css', 'resources/assets/js/site.js'])
        @endunless

        @include('layout._favicon')
    </head>
    <body class="home">
        <main>
            @include('layout._header')
            @yield('main-content')
            @include('layout._footer')
        </main>
    </body>
</html>
