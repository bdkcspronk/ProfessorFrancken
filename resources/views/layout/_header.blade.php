{{-- Menu items that go to another website open in a new tab and show an icon --}}
@php
    $isExternal = fn (string $url) : bool => \Illuminate\Support\Str::startsWith($url, ['http://', 'https://'])
        && parse_url($url, PHP_URL_HOST) !== request()->getHost();
@endphp
<header class="home-header">
    <a class="home-header__brand" href="/" aria-label="T.F.V. ‘Professor Francken’, home">
        <span class="home-header__logo">
            @svg('LOGO_FRANCKEN_BALLS', 'home-header__logo-svg', ['data-logo-balls' => 'true'])
        </span>
    </a>

    <nav class="home-nav" aria-label="Main navigation">
        @foreach ($items as $item)
            <div class="home-nav__item">
                <a class="home-nav__link {{ $item['class'] ?? '' }}" href="{{ $item['url'] }}" @if ($isExternal($item['url'])) target="_blank" rel="noopener" title="Opens {{ parse_url($item['url'], PHP_URL_HOST) }} in a new tab" @endif>
                    {{ $item['title'] }}
                    @if ($isExternal($item['url']))
                        <i class="fas fa-external-link-alt home-nav__external" aria-hidden="true"></i>
                    @endif
                    @if (count($item['subItems']) > 0)
                        <i class="fas fa-chevron-down home-nav__chevron" aria-hidden="true"></i>
                    @endif
                </a>

                @if (count($item['subItems']) > 0)
                    <div class="home-nav__dropdown">
                        @foreach ($item['subItems'] as $subItem)
                            <a class="home-nav__sub-link" href="{{ $subItem['url'] }}" @if ($isExternal($subItem['url'])) target="_blank" rel="noopener" @endif>
                                <i class="{{ $subItem['icon'] ?? '' }} fa-fw" aria-hidden="true"></i>
                                <span>
                                    {{ $subItem['title'] }}
                                    @if ($isExternal($subItem['url']))
                                        <i class="fas fa-external-link-alt home-nav__external" aria-hidden="true"></i>
                                    @endif
                                    @if (($subItem['description'] ?? '') !== '')
                                        <small>{{ $subItem['description'] }}</small>
                                    @endif
                                    @if ($isExternal($subItem['url']))
                                        <small class="home-nav__external-note">Opens {{ preg_replace('/^www\./', '', parse_url($subItem['url'], PHP_URL_HOST)) }} in a new tab</small>
                                    @endif
                                </span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </nav>

    {{-- The mobile menu uses details / summary so that it works without javascript --}}
    <details class="home-menu">
        <summary class="home-menu__toggle" aria-label="Menu">
            <span></span><span></span><span></span>
        </summary>
        <nav class="home-menu__panel" aria-label="Main navigation">
            {{-- Items with a dropdown open when they are tapped, like the menu itself --}}
            @foreach ($items as $item)
                @if (count($item['subItems']) > 0)
                    <details class="home-menu__group">
                        <summary class="home-menu__link">
                            {{ $item['title'] }}
                            <i class="fas fa-chevron-down home-menu__chevron" aria-hidden="true"></i>
                        </summary>
                        <a class="home-menu__sub-link" href="{{ $item['url'] }}">Overview</a>
                        @foreach ($item['subItems'] as $subItem)
                            <a class="home-menu__sub-link" href="{{ $subItem['url'] }}" @if ($isExternal($subItem['url'])) target="_blank" rel="noopener" @endif>
                                {{ $subItem['title'] }}
                                @if ($isExternal($subItem['url']))
                                    <i class="fas fa-external-link-alt home-nav__external" aria-label="opens in a new tab"></i>
                                @endif
                            </a>
                        @endforeach
                    </details>
                @else
                    <a class="home-menu__link" href="{{ $item['url'] }}" @if ($isExternal($item['url'])) target="_blank" rel="noopener" @endif>
                        {{ $item['title'] }}
                        @if ($isExternal($item['url']))
                            <i class="fas fa-external-link-alt home-nav__external" aria-label="opens in a new tab"></i>
                        @endif
                    </a>
                @endif
            @endforeach
        </nav>
    </details>
</header>

@auth
<div class="container">
    <x-borrelcie-notifications />
    @stack('notifications')
</div>
@endauth
