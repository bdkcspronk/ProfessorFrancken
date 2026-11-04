<!doctype html>
<html lang="en" class="h-100">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        @include('layout._seo')
        @include('layout._favicon')
        @include('admin._styles')
    </head>
    <body class="admin-page">
        {{-- The header of the website, with a tab for every section of the admin, a link back
             to the website and a button to log out --}}
        <header class="home-header admin-header">
            <a class="home-header__brand" href="/admin" aria-label="Administration of T.F.V. ‘Professor Francken’">
                <span class="home-header__logo">
                    @include('layout._logo-balls')
                </span>
            </a>

            <x-admin-navigation variant="sections" />

            <div class="home-header__actions">
                <a class="home-header__action" href="/" title="Back to the website">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    <span class="d-none d-xl-inline">Website</span>
                </a>

                <a class="home-header__action" href="{{ route('get-logout') }}" title="Log out">
                    <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                    <span class="d-none d-xl-inline">Log out</span>
                </a>
            </div>
        </header>

        {{-- The title of the page and its buttons in a crimson hero, like on the website --}}
        <section class="page-hero admin-hero" data-fcc-lattice>
            <div class="admin-container">
                <div class="admin-hero__row">
                    <h1 class="page-hero__title">@yield('page-title', 'Administration')</h1>

                    <div class="admin-hero__actions">
                        @yield('actions')
                    </div>
                </div>
            </div>
        </section>

        {{-- The pages of the section of this page, as tabs below the hero --}}
        <x-admin-navigation variant="section-pages" />

        <main class="admin-content admin-container">
            @if (session('status'))
                <p class="alert alert-success mb-3">
                    {{ session('status') }}
                </p>
            @endif

            @if (session('error'))
                <p class="alert alert-danger mb-3">
                    <strong>Error: </strong> {{ session('error') }}
                </p>
            @endif

            @section('alerts')
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <h3 class='h5'>We've found some validation errors while processing your request</h3>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            @show

            @yield('content')
        </main>

        @include('admin._scripts')
    </body>
</html>
