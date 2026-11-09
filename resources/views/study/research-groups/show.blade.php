@extends('layout.two-column-layout')

@section('page-title')
    {{ $group->title }}
@endsection
@section('title', $group->title . " - Research Groups - T.F.V. ‘Professor Francken’")

@section('content')
    <div class="committee-page research-group">
        @if ($group->photo)
            <img class="research-group__photo" src="{{ image($group->photo, ['width' => 1000, 'height' => 400]) }}" alt="">
        @endif

        <div class="cms-page">
            {!! $group->compiled_content !!}
        </div>

        @if ($group->units->isNotEmpty())
            <h2 class="home-heading committee-members__heading">
                {{ $group->units->count() === 1 ? 'Group' : 'Groups' }}
            </h2>
            <ul class="research-units">
                @foreach ($group->units as $unit)
                    <li class="research-unit">
                        @if ($unit->photo)
                            <img class="research-unit__photo" src="{{ image($unit->photo, ['width' => 200, 'height' => 200]) }}" alt="">
                        @endif
                        <div>
                            <h3 class="research-unit__name">{{ $unit->name }} group</h3>
                            <p class="research-unit__title">{{ $unit->title }}</p>
                            @if ($unit->website_url)
                                <a class="research-unit__link" href="{{ $unit->website_url }}">Website</a>
                            @endif
                            @if ($unit->contact_url)
                                <a class="research-unit__link" href="{{ $unit->contact_url }}">Contact</a>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection

@section('aside')
    <nav class="committee-nav">
        <h2 class="home-heading">Research groups</h2>
        <ul class="list-unstyled mb-0">
            @foreach ($groups as $other)
                <li>
                    <a
                        href="/study/research-groups/{{ $other->slug }}"
                        @class(['committee-nav__link', 'is-active' => $other->is($group)])
                    >
                        @if ($other->photo)
                            <img class="committee-nav__logo committee-nav__logo--photo" src="{{ image($other->photo, ['width' => 75, 'height' => 75]) }}" alt="">
                        @endif
                        {{ $other->title }}
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>
@endsection
