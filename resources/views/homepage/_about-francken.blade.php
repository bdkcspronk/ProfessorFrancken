<section class="container home-about">
    <div class="row">
        <div class="col-lg-6 mb-5 mb-lg-0">
            <div class="home-about__professor">
                <img alt="Portrait of professor Jan Carel Francken" src="{{ image("https://professorfrancken.nl/images/prof-jan-carel-francken.png", ['width' => 300, 'height' => 300]) }}" class="home-about__portrait" />
                <div>
                    <h2 class="home-heading">About the association</h2>
                </div>
            </div>
            <p class="home-about__intro">
                T.F.V. ‘Professor Francken’ is the study association for Engineering Physics at the University of Groningen.
                It is named after Groningen’s first professor of Applied Physics and is for students and staff of the applied physics departments.
            </p>
            <p>
                With over 1100 members we organise field trips in the Netherlands, an annual symposium,
                a foreign excursion and many study and career related activities.
            </p>
            <a class="home-link" href="/association">
                More about the association <i class="fas fa-arrow-right" aria-hidden="true"></i>
            </a>
        </div>

        <div class="col-lg-5 offset-lg-1">
            <div class="home-francken-vrij">
                <img alt="The covers of the three Francken Vrij editions of year 29" src="/images/francken-vrij-covers.png" class="home-francken-vrij__image"/>
                <h2 class="home-heading">Francken Vrij</h2>
                <p>
                    Our popular science magazine, with a different theme every edition:
                    articles from research groups, stories of members and a challenging puzzle.
                </p>
                <div class="home-francken-vrij__actions">
                    @isset($latest_edition)
                        <a class="btn home-btn home-btn--crimson" href="{{ $latest_edition->pdf() }}">
                            Read the latest edition
                        </a>
                    @endisset
                    <a class="home-link" href="/association/francken-vrij">
                        Archive <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
