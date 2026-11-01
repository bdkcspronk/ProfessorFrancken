<section class="home-hero" data-fcc-lattice>
    @if ($header_image)
        <div class="home-hero__photo-frame">
            <div class="home-hero__photo" style="background-image: url({{ image($header_image, ['width' => 1200, 'height' => 0, 'resize' => true], true) }})"></div>
        </div>
    @endif

    <div class="container home-hero__container">
        <div class="home-hero__body">
            <p class="home-hero__eyebrow">Study association for engineering physics</p>
            <h1 class="home-hero__title">T.F.V. ‘Professor<br><span class="home-hero__name">Francken</span><span class="home-hero__quote">’</span></h1>

            <div class="home-hero__actions">
                @guest
                    <a class="btn home-btn home-btn--crimson" href="/register">Become a member</a>
                @endguest
                <a class="btn home-btn home-btn--outline" href="/association/activities">All activities</a>
            </div>
        </div>
    </div>
</section>
