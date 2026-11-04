<!doctype html>
<html lang="en" class="h-100">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        @include('layout._seo')

        @unless(config('env') == 'testing')
            @vite(['resources/assets/sass/app.scss', 'resources/assets/css/vendor.css', 'resources/assets/js/site.js'])
        @endunless

        @stack('styles')

        @include('layout._favicon')
    </head>
    <body class="h-100 public-site">
        <main class="d-flex flex-column justify-content-between h-100">
            <div>
                @include('layout._header')
                @include('layout._page-hero')
                @yield('main-content')
            </div>
            @include("layout._footer")
        </main>

        @stack('scripts')
    </body>
</html>
