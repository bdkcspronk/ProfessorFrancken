<header class="home-header">
    <a class="home-header__brand" href="/" aria-label="T.F.V. ‘Professor Francken’, home">
        <span class="home-header__logo">
            @svg('LOGO_FRANCKEN_BALLS', 'home-header__logo-svg', ['data-logo-balls' => 'true'])
        </span>
    </a>

    <nav class="home-nav" aria-label="Main navigation">
        @foreach ($items as $item)
            <div class="home-nav__item">
                <a class="home-nav__link {{ $item['class'] ?? '' }}" href="{{ $item['url'] }}">
                    {{ $item['title'] }}
                    @if (count($item['subItems']) > 0)
                        <i class="fas fa-chevron-down home-nav__chevron" aria-hidden="true"></i>
                    @endif
                </a>

                @if (count($item['subItems']) > 0)
                    <div class="home-nav__dropdown">
                        @foreach ($item['subItems'] as $subItem)
                            <a class="home-nav__sub-link" href="{{ $subItem['url'] }}">
                                <i class="{{ $subItem['icon'] ?? '' }} fa-fw" aria-hidden="true"></i>
                                <span>
                                    {{ $subItem['title'] }}
                                    @if (($subItem['description'] ?? '') !== '')
                                        <small>{{ $subItem['description'] }}</small>
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
            @foreach ($items as $item)
                <a class="home-menu__link" href="{{ $item['url'] }}">{{ $item['title'] }}</a>
                @foreach ($item['subItems'] as $subItem)
                    <a class="home-menu__sub-link" href="{{ $subItem['url'] }}">{{ $subItem['title'] }}</a>
                @endforeach
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
