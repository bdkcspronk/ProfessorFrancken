<div class="card">
    <div class="card-body">
        <h4 class="font-weight-bold h5">
            Continue a committee
        </h4>
        <p class="small text-muted">
            Add a committee of a previous board to this board, with its logo, photo and description, so that you don't have to fill those in again.
        </p>
        <input
            type="search"
            class="form-control form-control-sm mb-2"
            placeholder="Search previous committees"
            aria-label="Search previous committees"
            data-filter-committees
        >
        <ul class="list-unstyled admin-continuable" id="continuable-committees">
            @foreach ($continueable_committees as $committee)
                <li class="p-2 my-2 bg-light" data-committee-name="{{ strtolower($committee->name) }}">
            {!!
               Form::model(
                   $committee,
                   [
                       'url' => action(
                           [\Francken\Association\Committees\Http\AdminContinueCommitteesController::class, 'store'],
                           ['board' => $board]
                       ),
                       'method' => 'post',
                       'class' =>  'd-flex justify-content-between align-items-center'
                   ]
               )
            !!}
                    {!! Form::hidden("committee_id", $committee->id) !!}
                    <a href="{{ action(
                                [\Francken\Association\Committees\Http\AdminCommitteesController::class, 'show'],
                                ['committee' => $committee, 'board' => $committee->board]
                                ) }}"
                       class="d-flex justify-content-start align-items-center"
                    >
                        <img
                            class="rounded mr-2 my-2"
                            src="{{ $committee->logo }}"
                            style="
                                   width: 60px;
                                   max-width: 60px;
                                   max-height: 40px;
                                   object-fit: contain;"
                        />
                        <div class="d-flex flex-column justify-content-between" >
                            <span class="font-weight-bold">
                                {{ $committee->name }}
                            </span>

                            <small class="text-muted">
                                {{ $committee->board->board_name->toString() }}
                            </small>
                        </div>
                    </a>
                    <div class="ml-auto d-flex align-items-center">
                        <button class="btn btn-text text-primary btn-sm" type="submit">
                            {{-- Added for tests.. --}}
                            <small class="text-primary mr-2 d-none">
                                Restart committee
                            </small>
                            <i class="fas fa-plus text-muted"></i>
                        </button>
                    </div>
                    {!!  Form::close() !!}
                </li>
            @endforeach
        </ul>
    </div>
</div>

@push('scripts')
    <script>
        // Only the previous committees whose name contains the search
        document.querySelectorAll('[data-filter-committees]').forEach(function (input) {
            input.addEventListener('input', function () {
                var search = input.value.trim().toLowerCase();
                document.querySelectorAll('[data-committee-name]').forEach(function (committee) {
                    committee.hidden = search !== '' && committee.dataset.committeeName.indexOf(search) === -1;
                });
            });
        });
    </script>
@endpush
