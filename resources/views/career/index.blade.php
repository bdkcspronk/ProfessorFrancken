@extends('layout.one-column-layout')

@section('page-title')
    Career
@endsection

@section('header-image-url', '/images/header/oslo.jpg')
@section('title', "Career - T.F.V. ‘Professor Francken’")

@section('content')
    <div class="page-intro">
        <p>At T.F.V. ‘Professor Francken’ we aim to help students prepare for their professional career after their studies. We do this by organizing <a href="/association/activities">lunch lectures, excursions, symposia and other career related activities</a>. Additionally we have a large network of alumni who work at various companies and might be of help when you're looking for a <a href="/career/job-openings">job or an internship</a>.</p>
    </div>

    <div class="row">
        <div class="col-md-6 col-lg-3 mb-4">
            <a class="home-pillar" href="/career/job-openings">
                <span class="home-pillar__icon"><i class="fas fa-search-dollar" aria-hidden="true"></i></span>
                <h2 class="home-pillar__title">Job openings</h2>
                <p class="home-pillar__text">Find job openings and internships related to engineering physics.</p>
                <span class="home-link">Read more <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
            </a>
        </div>
        <div class="col-md-6 col-lg-3 mb-4">
            <a class="home-pillar" href="/career/companies">
                <span class="home-pillar__icon"><i class="fas fa-building" aria-hidden="true"></i></span>
                <h2 class="home-pillar__title">Company profiles</h2>
                <p class="home-pillar__text">Get to know the companies related to engineering physics.</p>
                <span class="home-link">Read more <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
            </a>
        </div>
        <div class="col-md-6 col-lg-3 mb-4">
            <a class="home-pillar" href="/association/activities">
                <span class="home-pillar__icon"><i class="fas fa-route" aria-hidden="true"></i></span>
                <h2 class="home-pillar__title">Excursions</h2>
                <p class="home-pillar__text">Lunch lectures, excursions and other career activities appear in our calendar.</p>
                <span class="home-link">Read more <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
            </a>
        </div>
        <div class="col-md-6 col-lg-3 mb-4">
            <a class="home-pillar" href="https://www.linkedin.com/company/t.f.v.-professor-francken/">
                <span class="home-pillar__icon"><i class="fab fa-linkedin-in" aria-hidden="true"></i></span>
                <h2 class="home-pillar__title">LinkedIn</h2>
                <p class="home-pillar__text">Stay in touch with our alumni on LinkedIn.</p>
                <span class="home-link">Read more <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
            </a>
        </div>
    </div>
@endsection
