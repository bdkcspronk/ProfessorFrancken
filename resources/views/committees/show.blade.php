@extends('layout.two-column-layout')

@section('page-title')
    {{ $committee->name }}
@endsection

@section('content')
    <div class="committee-page">
        @if (! is_null($committee->logo))
            <div class="committee-page__logo">
                <img class="img-fluid" alt="" src="{{ image($committee->logo, ['height' => 500, 'width' => 500]) }}"/>
            </div>
        @endif

        @yield('committee-content')

        @if (! is_null($committee->photo))
            <img
                class="img-fluid committee-page__photo"
                src="{{ image($committee->photo, ['height' => 400, 'width' => 660, 'resize' => true]) }}"
                alt="Photo of the {{ $committee->name }}"
            >
        @endif

        @include('committees._members', ['members' => $committee->members ])
    </div>
@endsection

@section('aside')
    <nav class="committee-nav">
        <h2 class="home-heading">Committees</h2>
        <ul class="list-unstyled mb-0">
            {{-- Mark the active committee with a crimson background --}}
            <?php $id = $committee->id; ?>

            @foreach ($committees as $committee)
                <li>
                    <a
                        href="{{ action([\Francken\Association\Committees\Http\CommitteesController::class, 'show'], ['board' => $committee->board, 'committee' => $committee]) }}"
                        @class(['committee-nav__link', 'is-active' => $committee->id == $id])
                    >
                        @if (! is_null($committee->logo))
                            <img
                                class="committee-nav__logo"
                                src="{{ image($committee->logo, ['height' => 75, 'width' => 75]) }}"
                                alt=""
                            >
                        @else
                            <span class="committee-nav__logo committee-nav__logo--empty" aria-hidden="true">
                                <i class="fas fa-users"></i>
                            </span>
                        @endif
                        {{ $committee->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>
@endsection

@section('header-image')
    @component('layout.header._header_image')
    <div class="header-image__title">

    </div>
    @endcomponent
@endsection
