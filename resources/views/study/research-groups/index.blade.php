@extends('layout.one-column-layout')

@section('page-title')
    Research Groups
@endsection
@section('title', "Research Groups - T.F.V. ‘Professor Francken’")

@section('content')
    <div class="page-intro">
        <p>Most research groups of engineering physics are part of the <a href="https://www.rug.nl/research/zernike/">Zernike Institute for Advanced Materials</a>. It got its name in 2007, but grew out of the Materials Science Centre that started back in 1970. Physicists and chemists, and more and more biologists, theoreticians and experimentalists work closely together, from modelling and synthesis to the properties and functions of devices.</p>
        <p>Looking for a group for your bachelor or master thesis? Below you find what each group works on, and who to contact.</p>
    </div>

    <div class="row">
        @foreach ($groups as $group)
            <div class="col-md-6 col-lg-4 mb-4">
                <a class="home-pillar menu-card" href="/study/research-groups/{{ str_slug($group['title']) }}">
                    <img class="menu-card__image" src="{{ image($group['photo'], ['width' => 600, 'height' => 300]) }}" alt="">
                    <h2 class="home-pillar__title">{{ $group['title'] }}</h2>
                    <p class="home-pillar__text">{{ str_limit(strip_tags($group['description']), 140) }}</p>
                    <span class="home-link">Read more <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
                </a>
            </div>
        @endforeach
    </div>
@endsection
