<li class="p-3 mb-2">
    <div class="d-flex justify-content-between">
        <h3 class="section-header" >
            @if ($board->name != '')
                ‘{{ $board->name }}’
                <small class="ml-2">
                    {{ $board->board_year->toString() }}
                </small>
            @else
                {{ $board->board_year->toString() }}
            @endif
        </h3>

        <div>
            <a href="{{ action([\Francken\Association\Boards\Http\Controllers\AdminBoardsController::class, 'edit'], $board->id ?? 1) }}"
               class="btn btn-sm btn-outline-primary"
            >
                <i class="far fa-edit"></i>
                Edit
            </a>
        </div>
    </div>

    {{-- The photo of the board next to its members; the list grows with the number of members,
         so that boards of more than five members fit as well --}}
    <div class="admin-board mt-2">
        <img
            class="admin-board__photo"
            src="{{ board_banner_image($board->photo, ['vertical-offset' => $board->photo_position]) }}"
            alt="Photo of the board {{ $board->name }}"
            loading="lazy"
        >

        <ul class="admin-board__members">
            @foreach($board->members as $member)
                <li class="admin-board__member">
                    @if (isset($member->photo))
                        <img
                            class="admin-board__member-photo"
                            src="{{ image($member->photo, ['width' => 90, 'height' => 90]) }}"
                            alt=""
                            loading="lazy"
                        >
                    @else
                        <span class="admin-board__member-photo" aria-hidden="true"></span>
                    @endif
                    <span class="admin-board__member-name">{{ $member->name }}</span>
                    <span class="admin-board__member-title">{{ $member->title }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</li>
