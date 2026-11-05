@extends('admin.layout')
@section('page-title', 'Committees')

@section('content')
    <div class="admin-committees">
        <div>
            <p class="admin-committees__summary">
                {{ $committees->count() }} {{ Str::plural('committee', $committees->count()) }} of the board
                <strong>{{ $board->board_name->toString() }}</strong>,
                with {{ $committees->sum(fn ($committee) => $committee->committeeMembers()->count()) }} committee members.
            </p>

            @if ($committees->isEmpty())
                <p class="text-muted">
                    This board doesn't have any committees yet. Start one with the button above, or continue a committee of a previous board on the right.
                </p>
            @endif

            {{-- A card for every committee, with its members --}}
            <div class="admin-committee-grid">
                @foreach ($committees as $committee)
                    @php
                        $url = action([\Francken\Association\Committees\Http\AdminCommitteesController::class, 'show'], ['committee' => $committee, 'board' => $board]);
                        $members = $committee->committeeMembers();
                    @endphp
                    <article class="admin-committee">
                        <a href="{{ $url }}" class="admin-committee__header">
                            <span class="admin-committee__logo">
                                @if ($committee->logo)
                                    <img src="{{ $committee->logo }}" alt="" loading="lazy">
                                @else
                                    <i class="fas fa-users" aria-hidden="true"></i>
                                @endif
                            </span>
                            <span class="admin-committee__name">
                                {{ $committee->name }}
                                @unless ($committee->is_public)
                                    <span class="badge badge-secondary" title="Not shown on the website">Hidden</span>
                                @endunless
                            </span>
                        </a>

                        @if ($committee->email)
                            <a class="admin-committee__email" href="mailto:{{ $committee->email }}">
                                <i class="far fa-envelope" aria-hidden="true"></i> {{ $committee->email }}
                            </a>
                        @endif

                        <div class="admin-committee__members">
                            <span class="admin-committee__count">
                                <i class="fas fa-user-friends" aria-hidden="true"></i>
                                {{ $members->count() }} {{ Str::plural('member', $members->count()) }}
                            </span>
                            @if ($members->isNotEmpty())
                                <ul class="admin-committee__member-list">
                                    @foreach ($members as $member)
                                        <li>{{ $member->member->fullname }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <span class="admin-committee__updated">
                            Last updated {{ $committee->updated_at->diffForHumans() }}
                        </span>
                    </article>
                @endforeach
            </div>
        </div>

        <aside>
            @include('admin.association.committees._continuable-committees', ['continueable_committees' => $continueable_committees])
        </aside>
    </div>
@endsection

@section('actions')
    <div class="d-flex align-items-start">

        {!!
           Form::open(
               [
                   'url' => action(
                       [\Francken\Association\Committees\Http\AdminRedirectCommitteesController::class, 'index']
                   ),
                   'method' => 'get',
                   'class' => 'form-inline mr-3',
               ]
           )
        !!}
        <x-forms.select name="board_id" :value="$board->id" :options="$board_years">
            <x-slot name="label">
                <span class="mx-3">Board</span>
            </x-slot>
        </x-forms.select>

        {{-- Without javascript the board is opened with this button --}}
        <button class='btn btn-primary btn-sm ml-3' data-board-view>
            <i class="fas fa-eye"></i>
            View
        </button>
        {!! Form::close() !!}

        <a href="{{ action([\Francken\Association\Committees\Http\AdminActiveMembersExportController::class, 'index'], ['board' => $board]) }}"
           class="btn btn-primary btn-sm mr-3"
        >
            <i class="fas fa-cloud-download-alt"></i>
            Export active members
        </a>

        <a href="{{ action([\Francken\Association\Committees\Http\AdminCommitteesController::class, 'create'], ['board' => $board]) }}"
           class="btn btn-primary btn-sm"
        >
            <i class="fas fa-plus"></i>
            Start a committee
        </a>
    </div>
@endsection

@push('scripts')
    <script>
        // Choosing a board opens its committees right away, so the View button isn't needed
        document.querySelectorAll('select[name="board_id"]').forEach(function (select) {
            var view = select.form.querySelector('[data-board-view]');
            if (view) {
                view.hidden = true;
            }
            select.addEventListener('change', function () {
                select.form.submit();
            });
        });
    </script>
@endpush
