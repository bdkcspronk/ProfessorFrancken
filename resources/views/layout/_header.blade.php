{{-- Menu items that go to another website open in a new tab and show an icon --}}
@php
    $isExternal = fn (string $url) : bool => \Illuminate\Support\Str::startsWith($url, ['http://', 'https://'])
        && parse_url($url, PHP_URL_HOST) !== request()->getHost();

    // The last item of the menu is Login, or the Profile when logged in
    $account = collect($items)->first(fn (array $item) : bool => $item['url'] === '/profile' || ($item['class'] ?? '') === 'login-link');

    // Login and the profile have their own button in the header, so the menu leaves them out
    $menuItems = array_filter($items, fn (array $item) : bool => $item !== $account);
@endphp
<header class="home-header">
    <a class="home-header__brand" href="/" aria-label="T.F.V. ‘Professor Francken’, home">
        <span class="home-header__logo">
            @svg('LOGO_FRANCKEN_BALLS', 'home-header__logo-svg', ['data-logo-balls' => 'true'])
        </span>
    </a>

    {{-- Search, login (or the profile) and the menu with all pages are the only items of the header --}}
    <div class="home-header__actions">
        {{-- The search icon opens a field to its left and then searches; without javascript it
             goes to the search page --}}
        <form class="header-search" action="{{ route('search') }}" method="get" role="search" data-header-search>
            <input class="header-search__input" type="search" name="q" placeholder="Search" aria-label="Search the website" tabindex="-1">
            <button class="home-header__action header-search__button" type="submit" aria-label="Search" title="Search" aria-expanded="false">
                <i class="fas fa-search" aria-hidden="true"></i>
            </button>
        </form>

        {{-- An outlined person to log in, a filled person that opens the pages of the profile.
             Without javascript the filled person goes to the profile. See public/js/AccountMenu.js --}}
        @if ($account !== null && $account['url'] === '/profile')
            <div class="account-menu" data-account-menu>
                <a class="home-header__action" href="{{ $account['url'] }}" aria-label="{{ $account['title'] }}" title="{{ $account['title'] }}" aria-haspopup="true" aria-expanded="false" aria-controls="account-menu" data-account-menu-toggle>
                    <i class="fas fa-user" aria-hidden="true"></i>
                </a>
                <ul class="account-menu__list" id="account-menu">
                    <li>
                        <a class="account-menu__link" href="{{ $account['url'] }}">
                            <i class="fas fa-user fa-fw account-menu__icon" aria-hidden="true"></i>
                            {{ $account['title'] }}
                        </a>
                    </li>
                    @foreach ($account['subItems'] as $subItem)
                        <li>
                            <a class="account-menu__link" href="{{ $subItem['url'] }}">
                                <i class="{{ $subItem['icon'] ?? '' }} fa-fw account-menu__icon" aria-hidden="true"></i>
                                {{ $subItem['title'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @elseif ($account !== null)
            <a class="home-header__action" href="{{ $account['url'] }}" aria-label="{{ $account['title'] }}" title="{{ $account['title'] }}">
                <i class="far fa-user" aria-hidden="true"></i>
            </a>
        @endif

        {{-- The three lines turn into a cross while the menu is open --}}
        <button class="home-header__action site-menu-toggle" type="button" aria-label="Menu" aria-controls="site-menu" aria-expanded="false" data-site-menu-toggle>
            <span class="site-menu-toggle__icon" aria-hidden="true"><span></span><span></span><span></span></span>
        </button>
    </div>
</header>

{{--
    The menu with all pages: a panel that slides in from the right below the header, with
    the sections of the menu. A section with pages of its own slides those in, with a button
    to go back. The Menu button of the header closes it again.
    See public/js/SiteMenu.js
--}}
<div class="site-menu" id="site-menu" data-site-menu>
    <div class="site-menu__backdrop" data-site-menu-close></div>

    <nav class="site-menu__panel" aria-label="All pages" data-fcc-lattice>
        <div class="site-menu__level is-active" data-site-menu-level="main">
            <ul class="site-menu__list">
                @foreach ($menuItems as $item)
                    <li>
                        @if (count($item['subItems']) > 0)
                            <button class="site-menu__link" type="button" data-site-menu-open="{{ \Illuminate\Support\Str::slug($item['title']) }}">
                                {{ $item['title'] }}
                                <i class="fas fa-chevron-right site-menu__arrow" aria-hidden="true"></i>
                            </button>
                        @else
                            <a class="site-menu__link" href="{{ $item['url'] }}" @if ($isExternal($item['url'])) target="_blank" rel="noopener" @endif>
                                {{ $item['title'] }}
                                @if ($isExternal($item['url']))
                                    <i class="fas fa-external-link-alt site-menu__arrow" aria-label="opens in a new tab"></i>
                                @endif
                            </a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>

        @foreach ($menuItems as $item)
            @if (count($item['subItems']) > 0)
                <div class="site-menu__level" data-site-menu-level="{{ \Illuminate\Support\Str::slug($item['title']) }}">
                    <div class="site-menu__top">
                        <button class="site-menu__back" type="button" data-site-menu-back>
                            <i class="fas fa-chevron-left" aria-hidden="true"></i>
                            Back
                        </button>
                    </div>

                    <a class="site-menu__section" href="{{ $item['url'] }}">
                        {{ $item['title'] }}
                        <span class="site-menu__overview">Overview <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
                    </a>

                    <ul class="site-menu__list">
                        @foreach ($item['subItems'] as $subItem)
                            <li>
                                <a class="site-menu__sub-link" href="{{ $subItem['url'] }}" @if ($isExternal($subItem['url'])) target="_blank" rel="noopener" @endif>
                                    <i class="{{ $subItem['icon'] ?? '' }} fa-fw site-menu__icon" aria-hidden="true"></i>
                                    <span>
                                        {{ $subItem['title'] }}
                                        @if ($isExternal($subItem['url']))
                                            <i class="fas fa-external-link-alt home-nav__external" aria-label="opens in a new tab"></i>
                                        @endif
                                        @if (($subItem['description'] ?? '') !== '')
                                            <small>{{ $subItem['description'] }}</small>
                                        @endif
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endforeach
    </nav>
</div>

@auth
<div class="container">
    <x-borrelcie-notifications />
    @stack('notifications')
</div>
@endauth
