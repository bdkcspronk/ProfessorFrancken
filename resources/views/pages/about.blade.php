@extends('layout.one-column-layout')

@section('page-title')
    About us
@endsection
@section('title', "About us - T.F.V. ‘Professor Francken’")
@section('description', "T.F.V. ‘Professor Francken’ is the study association for engineering physics at the University of Groningen.")

@section('content')
    <div class="page-intro">
        <p>The Technisch Fysische Vereniging ‘Professor Francken’ (T.F.V. ‘Professor Francken’) is the study association for engineering physics at the University of Groningen. We are there for students and graduates of engineering physics and of closely related programmes. Students from other programmes of the faculty are welcome too: as a donor they enjoy the same privileges as our members, except the right to vote at the general members assembly (ALV).</p>
        <p>The association was founded in 1968 as the “Vereniging van Toekomstige Ingenieurs” (Association of Future Engineers). At the time it was still uncertain whether applied physics graduates in Groningen would be granted the title ‘ingenieur’. A Royal Decree of 18 November 1971 settled that, and the association changed its name to V.K.T.N., the Vereniging voor Kandidaten Technische Natuurkunde (Association for Candidates in Applied Physics). In 1984 it took its current name, after the first professor of applied physics in Groningen: prof. dr. ir. J.C. Francken.</p>
    </div>

    {{-- The photo of the newest board, so that it changes every year --}}
    @php
        $newestBoard = \Francken\Association\Boards\Board::query()
            ->whereNotNull('photo_media_id')
            ->where('installed_at', '<', now())
            ->orderByDesc('installed_at')
            ->first();
    @endphp
    @include('layout._menu-cards', ['menu' => '/about', 'images' => [
        '/association/history' => '/images/history.jpg',
        '/association/honorary-members' => '/images/prof-jan-carel-francken.png',
        '/association/boards' => $newestBoard !== null ? board_banner_image($newestBoard->photo) : '/images/boards/statisch.jpg',
    ]])
@endsection
