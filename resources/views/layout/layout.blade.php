<!doctype html>
<html lang="en" class="h-100">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        @include('layout._seo')

        {{-- The fonts of the header and footer --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap">

        @unless(config('env') == 'testing')
            @vite('resources/assets/sass/app.scss')
        @endunless

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.7.1/katex.min.css" integrity="sha384-wITovz90syo1dJWVh32uuETPVEtGigN07tkttEqPv+uR2SE/mbQcG7ATL28aI9H0" crossorigin="anonymous">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.7.1/katex.min.js" integrity="sha384-/y1Nn9+QQAipbNQWU65krzJralCnuOasHncUFXGkdwntGeSvQicrYkiUBwsgUqc1" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.7.1/contrib/auto-render.min.js" integrity="sha384-dq1/gEHSxPZQ7DdrM82ID4YVol9BYyU7GbWlIwnwyPzotpoc57wDw/guX8EaYGPx" crossorigin="anonymous"></script>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />

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

        <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js"> </script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

        {{-- The modification time makes browsers load the scripts again after they changed --}}
        <script src="/js/FccLattice.js?v={{ filemtime(public_path('js/FccLattice.js')) }}"></script>
        <script src="/js/LogoBalls.js?v={{ filemtime(public_path('js/LogoBalls.js')) }}"></script>
        <script src="/js/ExternalLinks.js?v={{ filemtime(public_path('js/ExternalLinks.js')) }}"></script>
        <script src="/js/SiteMenu.js?v={{ filemtime(public_path('js/SiteMenu.js')) }}"></script>
        <script src="/js/AccountMenu.js?v={{ filemtime(public_path('js/AccountMenu.js')) }}"></script>
        <script src="/js/SiteSearch.js?v={{ filemtime(public_path('js/SiteSearch.js')) }}"></script>

        <script type="text/javascript">
         // Render  any latex currently loaded in our dom
         document.addEventListener("DOMContentLoaded", function() {
             renderMathInElement(document.body, {
                     delimiters: [
                         {left: "$$", right: "$$", display: true},
                         {left: "$", right: "$", display: false},
                         {left: "\\[", right: "\\]", display: true},
                         {left: "\\(", right: "\\)", display: false}
                     ]
                 }
             );
         });
        </script>

        @stack('scripts')
    </body>
</html>
