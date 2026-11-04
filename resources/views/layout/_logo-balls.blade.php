{{--
    The logo built from separate balls of an FCC unit cell (back, middle and front layer), so
    that public/js/LogoBalls.js can let them shrink near the mouse. The balls are filled with
    the current color so that front balls cover those behind. The coat of arms is a separate
    file, which browsers keep, as it is most of the size of the logo
--}}
<svg class="home-header__logo-svg" viewBox="0 0 200 158" xmlns="http://www.w3.org/2000/svg" data-logo-balls="true" aria-hidden="true" focusable="false">
    <g transform="matrix(1.252144,0,0,1.252144,0,0)">
        <use href="/logo/coat-of-arms.svg?v={{ filemtime(public_path('logo/coat-of-arms.svg')) }}#coat-of-arms"/>
        <g class="logo-balls" fill="currentColor" stroke="white" stroke-width="1.281">
            <circle class="logo-ball" data-depth="2" cx="48.369" cy="24.738" r="24.063"/>
            <circle class="logo-ball" data-depth="2" cx="116.429" cy="24.738" r="24.063"/>
            <circle class="logo-ball" data-depth="2" cx="116.429" cy="92.798" r="24.063"/>
            <circle class="logo-ball" data-depth="2" cx="48.369" cy="92.798" r="24.063"/>
            <circle class="logo-ball" data-depth="2" cx="82.399" cy="58.768" r="24.063"/>
            <circle class="logo-ball" data-depth="1" cx="91.659" cy="28.828" r="24.063"/>
            <circle class="logo-ball" data-depth="1" cx="125.689" cy="62.858" r="24.063"/>
            <circle class="logo-ball" data-depth="1" cx="91.659" cy="96.888" r="24.063"/>
            <circle class="logo-ball" data-depth="1" cx="57.629" cy="62.858" r="24.063"/>
            <circle class="logo-ball" data-depth="0" cx="66.889" cy="32.918" r="24.063"/>
            <circle class="logo-ball" data-depth="0" cx="134.949" cy="32.918" r="24.063"/>
            <circle class="logo-ball" data-depth="0" cx="134.949" cy="100.978" r="24.063"/>
            <circle class="logo-ball" data-depth="0" cx="66.889" cy="100.978" r="24.063"/>
            <circle class="logo-ball" data-depth="0" cx="100.919" cy="66.948" r="24.063"/>
        </g>
    </g>
</svg>
