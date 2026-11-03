@inject('settings', "Francken\Shared\Settings\Settings")
<footer class="home-footer">
    @if (isset($editPageUrl))
        <div class="container">
            <p class="text-center my-5">
                <a href="{{ $editPageUrl }}">
                    Edit this page on github
                </a>
            </p>
        </div>
    @endif

    <div class="home-footer__sponsors">
        <x-footer-sponsors/>
    </div>

    <div class="home-footer__main" data-fcc-lattice>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <a class="home-footer__brand" href="/">
                        @svg('LOGO_FRANCKEN_BALLS', 'home-footer__logo')
                        <span class="home-footer__name">T.F.V. ‘Professor<br><span class="home-hero__name">Francken</span><span class="home-hero__quote">’</span></span>
                    </a>
                    <p class="home-footer__tagline">
                        The study association for engineering physics at the University of Groningen.
                    </p>
                    <div class="home-footer__social">
                        <a href="https://www.instagram.com/tfvprofessorfrancken/" aria-label="Instagram">
                            <i class="fab fa-instagram" aria-hidden="true"></i>
                        </a>
                        <a href="https://www.linkedin.com/company/t.f.v.-professor-francken/?viewAsMember=true" aria-label="LinkedIn">
                            <i class="fab fa-linkedin-in" aria-hidden="true"></i>
                        </a>
                        <a href="https://github.com/ProfessorFrancken/ProfessorFrancken" aria-label="GitHub">
                            <i class="fab fa-github" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                <div class="col-sm-4 col-lg-2 offset-lg-1 mb-4 mb-sm-0">
                    <h2 class="home-footer__heading">Visit us</h2>
                    <address class="mb-0">
                        <a href="https://maps.app.goo.gl/ZzqTA98zP9sSvZLA6" target="_blank" rel="noopener">
                            Feringa Building<br>
                            Room 5614.0143
                        </a><br>
                        Nijenborgh 3<br>
                        9747 AG Groningen<br>
                        The Netherlands
                    </address>
                </div>

                <div class="col-sm-4 col-lg-3 mb-4 mb-sm-0">
                    <h2 class="home-footer__heading">Contact</h2>
                    <a class="home-footer__contact" href="mailto:board@professorfrancken.nl">
                        <i class="far fa-envelope fa-fw" aria-hidden="true"></i>
                        board@professorfrancken.nl
                    </a>
                    @if ($settings->contactNumberOfChair())
                        <a class="home-footer__contact" href="tel:{{ str_replace(' ', '', $settings->contactNumberOfChair()) }}">
                            <i class="fas fa-phone fa-fw" aria-hidden="true"></i>
                            {{ $settings->contactNumberOfChair() }}
                        </a>
                    @endif
                    <a class="home-footer__contact" href="/contact">
                        <i class="fas fa-address-card fa-fw" aria-hidden="true"></i>
                        More contact info
                    </a>
                </div>

                <div class="col-sm-4 col-lg-2">
                    <h2 class="home-footer__heading">Policies</h2>
                    <a href="/privacy-policy">Privacy policy</a><br>
                    <a href="/pdf/Code_of_Conduct.pdf">Code of conduct</a><br>
                    <a href="/cookies-policy">Cookies</a>
                    @impersonating($guard = null)
                        <br><a href="{{ route('impersonate.leave') }}">Leave impersonation</a>
                    @endImpersonating
                </div>
            </div>

            <p class="home-footer__copyright">&copy; {{ date('Y') }} T.F.V. ‘Professor Francken’</p>
        </div>
    </div>
</footer>
