@extends('layout.one-column-layout')

@section('page-title')
    Research Groups
@endsection
@section('title', "Research Groups - T.F.V. ‘Professor Francken’")

@section('content')
    @if ($introduction !== null && $introduction->compiled_content)
        <div class="page-intro">
            {!! $introduction->compiled_content !!}
        </div>
    @endif

    <div class="row">
        @foreach ($groups as $group)
            <div class="col-md-6 col-lg-4 mb-4">
                <a class="home-pillar menu-card" href="/study/research-groups/{{ $group->slug }}">
                    @if ($group->photo)
                        <img class="menu-card__image" src="{{ image($group->photo, ['width' => 600, 'height' => 300]) }}" alt="">
                    @endif
                    <h2 class="home-pillar__title">{{ $group->title }}</h2>
                    <p class="home-pillar__text">{{ str_limit(strip_tags($group->compiled_content ?? ''), 140) }}</p>
                    <span class="home-link">Read more <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
                </a>
            </div>
        @endforeach
    </div>
@endsection
