@extends('layout.two-column-layout')

@section('page-title')
    {{ $group['title'] }}
@endsection
@section('title', $group['title'] . " - Research Groups - T.F.V. ‘Professor Francken’")

@section('content')
    <div class="committee-page research-group">
        <img class="research-group__photo" src="{{ image($group['photo'], ['width' => 1000, 'height' => 400]) }}" alt="">

        <div class="cms-page">
            {!! $group['description'] !!}
        </div>

        <h2 class="home-heading committee-members__heading">
            {{ count($group['groups']) === 1 ? 'Group' : 'Groups' }}
        </h2>
        <ul class="research-units">
            @foreach ($group['groups'] as $unit)
                <li class="research-unit">
                    <img class="research-unit__photo" src="{{ image($unit['foto'], ['width' => 200, 'height' => 200]) }}" alt="">
                    <div>
                        <h3 class="research-unit__name">{{ $unit['group'] }} group</h3>
                        <p class="research-unit__title">{{ $unit['title'] }}</p>
                        <a class="research-unit__link" href="{{ $unit['link'] }}">Website</a>
                        <a class="research-unit__link" href="{{ $unit['contact'] }}">Contact</a>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
@endsection

@section('aside')
    <nav class="committee-nav">
        <h2 class="home-heading">Research groups</h2>
        <ul class="list-unstyled mb-0">
            @foreach ($groups as $other)
                <li>
                    <a
                        href="/study/research-groups/{{ str_slug($other['title']) }}"
                        @class(['committee-nav__link', 'is-active' => $other['title'] === $group['title']])
                    >
                        <img class="committee-nav__logo committee-nav__logo--photo" src="{{ image($other['photo'], ['width' => 75, 'height' => 75]) }}" alt="">
                        {{ $other['title'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>
@endsection
