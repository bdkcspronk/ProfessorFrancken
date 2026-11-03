{{--
    A card for every page in a menu of the header, given by the url of that menu. With
    images (by the url of a page) every card gets a banner: the image of the page, or a
    navy banner with its icon when it has none
--}}
@php
    $menuPages = collect(config('francken.navigation.menu'))->firstWhere('url', $menu)['subItems'] ?? [];
    $images = $images ?? null;
@endphp
<div class="row">
    @foreach ($menuPages as $menuPage)
        <div class="col-md-6 col-lg-4 mb-4">
            <a @class(['home-pillar', 'menu-card' => $images !== null]) href="{{ $menuPage['url'] }}">
                @if ($images === null)
                    <span class="home-pillar__icon"><i class="{{ $menuPage['icon'] }}" aria-hidden="true"></i></span>
                @elseif (isset($images[$menuPage['url']]))
                    <img class="menu-card__image" src="{{ $images[$menuPage['url']] }}" alt="">
                @else
                    <span class="menu-card__banner" data-fcc-lattice>
                        <i class="{{ $menuPage['icon'] }}" aria-hidden="true"></i>
                    </span>
                @endif
                <h2 class="home-pillar__title">{{ $menuPage['title'] }}</h2>
                <p class="home-pillar__text">{{ $menuPage['description'] }}</p>
                <span class="home-link">Read more <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
            </a>
        </div>
    @endforeach
</div>
