{{-- A board: a banner with its name and year (on the board photo when there is one), followed by its members.
     The photos of the first board load right away, the others when they get near the screen;
     public/js/BoardPhotos.js shows them from top to bottom --}}
@php($loading = ($first ?? false) ? 'eager' : 'lazy')
<article class="board">
    <header @class(['board__banner', 'board__banner--photo' => $board->photo !== null]) @if ($board->photo === null) data-fcc-lattice @endif>
        @if ($board->photo !== null)
            <img
                class="board__photo"
                loading="{{ $loading }}"
                @if ($first ?? false) fetchpriority="high" @endif
                data-board-photo
                src="{{ board_banner_image($board->photo, ['vertical-offset' => $board->photo_position]) }}"
                alt="Board photo of {{ $board->name != '' ? $board->board_name->toString() : $board->board_year->toString() }}"
            >
        @endif
        <div class="board__title">
            @if ($board->name != '')
                <div class="board__year">{{ $board->board_year->toString() }}</div>
                <h2 class="board__name">‘{{ $board->board_name->toString() }}’</h2>
            @else
                <h2 class="board__name">{{ $board->board_year->toString() }}</h2>
            @endif
        </div>
    </header>

    <ul class="board__members">
        @foreach($board->members as $member)
            <li class="board__member">
                {{-- Behind the photo is a slanted stripe, alternately crimson and navy --}}
                <span class="board__member-photo">
                    @if ($member->photo !== null)
                        <img
                            loading="{{ $loading }}"
                            data-board-photo
                            src="{{ image($member->photo, ['width' => 300, 'height' => 300]) }}"
                            alt="Photo of {{ $member->name }}"
                        >
                    @else
                        <i class="fas fa-user" aria-hidden="true"></i>
                    @endif
                </span>
                <h3 class="board__member-name">{{ $member->name }}</h3>
                <div class="board__member-title">{{ $member->title }}</div>
            </li>
        @endforeach
    </ul>

    @if ($board->photo !== null)
        <a class="home-link board__download" href="{{ $board->photo }}">
            <i class="fas fa-download" aria-hidden="true"></i>
            Download board photo
        </a>
    @endif
</article>
