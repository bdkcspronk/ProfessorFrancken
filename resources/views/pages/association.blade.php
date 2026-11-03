@extends('layout.one-column-layout')

@section('page-title')
    Association
@endsection
@section('title', "Association - T.F.V. ‘Professor Francken’")
@section('description', "The Technisch Fysische Vereniging ‘Professor Francken’ (Applied Physics Association ‘Professor Francken’) is the University of Groningen’s study association for applied physics and it is focused on (graduated) applied physics students.")

@section('content')
    <div class="page-intro">
        <p>The Technisch Fysische Vereniging ‘Professor Francken’ (Applied Physics Association ‘Professor Francken’) is the University of Groningen’s study association for engineering physics and it is focused on (graduated) applied physics students and students of closely related study tracks. Other students of the faculty can become a donator of the association. They have the same privileges as applied physics students, except the right to vote at the general members assembly (ALV).</p>
        <p>The association was founded in 1968 as the “Vereniging van Toekomstige Ingenieurs” (Association of Future Engineers). At that time it was uncertain whether applied physics graduates would be granted the qualification ‘ingenieur’. This changed for the Groningen technicians as a result of a Royal Decree of 18 November 1971. The association changed its name to V.K.T.N., or Vereniging voor Kandidaten Technische Natuurkunde (Association for Candidates Applied Physics). In 1984 the association was named after the first professor in applied physics in Groningen: prof. dr. ir. J.C. Francken.</p>
    </div>

    <div class="row">
        <div class="col-md-6 col-lg-4 mb-4">
            <a class="home-pillar" href="/association/activities">
                <span class="home-pillar__icon"><i class="fas fa-calendar-week" aria-hidden="true"></i></span>
                <h2 class="home-pillar__title">Activities</h2>
                <p class="home-pillar__text">Upcoming career, study and social activities, and the calendar of the whole academic year.</p>
                <span class="home-link">Read more <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
            </a>
        </div>
        <div class="col-md-6 col-lg-4 mb-4">
            <a class="home-pillar" href="/association/history">
                <span class="home-pillar__icon"><i class="far fa-clock" aria-hidden="true"></i></span>
                <h2 class="home-pillar__title">History</h2>
                <p class="home-pillar__text">Learn more about the history of Francken!</p>
                <span class="home-link">Read more <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
            </a>
        </div>
        <div class="col-md-6 col-lg-4 mb-4">
            <a class="home-pillar" href="/association/honorary-members">
                <span class="home-pillar__icon"><i class="fas fa-award" aria-hidden="true"></i></span>
                <h2 class="home-pillar__title">Honorary members</h2>
                <p class="home-pillar__text">Learn more about our honorary members.</p>
                <span class="home-link">Read more <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
            </a>
        </div>
        <div class="col-md-6 col-lg-4 mb-4">
            <a class="home-pillar" href="/association/boards">
                <span class="home-pillar__icon"><i class="fas fa-user-tie" aria-hidden="true"></i></span>
                <h2 class="home-pillar__title">Boards</h2>
                <p class="home-pillar__text">List of all previous boards and ‘Wijze Heeren en Damesch’.</p>
                <span class="home-link">Read more <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
            </a>
        </div>
        <div class="col-md-6 col-lg-4 mb-4">
            <a class="home-pillar" href="/association/committees">
                <span class="home-pillar__icon"><i class="fas fa-users" aria-hidden="true"></i></span>
                <h2 class="home-pillar__title">Committees</h2>
                <p class="home-pillar__text">Francken has a lot of fascinating committees. Come and see yourself.</p>
                <span class="home-link">Read more <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
            </a>
        </div>
        <div class="col-md-6 col-lg-4 mb-4">
            <a class="home-pillar" href="/association/francken-vrij">
                <span class="home-pillar__icon"><i class="fas fa-book-open" aria-hidden="true"></i></span>
                <h2 class="home-pillar__title">Francken Vrij</h2>
                <p class="home-pillar__text">The popular science magazine of the Technisch Fysische Vereniging ‘Professor Francken’.</p>
                <span class="home-link">Read more <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
            </a>
        </div>
    </div>
@endsection
