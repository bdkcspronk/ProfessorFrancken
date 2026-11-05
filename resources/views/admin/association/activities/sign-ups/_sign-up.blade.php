{{-- One sign up in the list of sign ups of an activity in the admin --}}
<li class="p-3 my-2 bg-light">
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            @isset($position)
                <span class="text-muted">{{ $position }}.</span>
            @endisset
            {{ $signUp->member->fullname }}
            @if ($signUp->plus_ones > 0)
                <strong>(+{{ $signUp->plus_ones }})</strong>
            @endif
            <small class="text-muted mx-1">
                ({{ $signUp->member->email->toString() }})
            </small>
            @if ($signUp->has_drivers_license)
                <i class="fas fa-car text-muted ml-1" title="Has a drivers license"></i>
            @endif
            @if ($signUp->is_offered)
                <span class="badge badge-warning ml-1" title="A place opened up for this member, which they haven't accepted yet">
                    Place offered, not yet accepted
                </span>
            @endif
        </h5>
        <div class="ml-auto d-flex align-items-center">
            @if ($signUp->is_offered)
                {!! Form::open(['url' => action([\Francken\Association\Activities\Http\AdminSignUpsController::class, 'approve'], ['activity' => $activity, 'sign_up' => $signUp]), 'class' => 'mb-0']) !!}
                    <button class="btn btn-sm btn-primary" type="submit" title="Make this place official, without waiting for the member">
                        <i class="fas fa-check mr-1"></i>
                        Approve
                    </button>
                {!! Form::close() !!}
            @endif
            <a
                class="btn btn-text text-primary btn-sm"
                href="{{  action(
                              [\Francken\Association\Activities\Http\AdminSignUpsController::class, 'edit'],
                              ['activity' => $activity, 'sign_up' => $signUp]
                              )
                      }}"
            >
                Edit
                <i class="fas fa-edit ml-1"></i>
            </a>
        </div>
    </div>

    @if ($signUp->has_place && $activity->waiting_list_sign_ups->isNotEmpty())
        <details class="mt-2">
            <summary class="text-primary small">
                <i class="fas fa-exchange-alt mr-1"></i>
                Replace with someone from the waiting list
            </summary>
            {!!
                Form::open([
                    'url' => action([\Francken\Association\Activities\Http\AdminSignUpsController::class, 'replace'], ['activity' => $activity, 'sign_up' => $signUp]),
                    'class' => 'form-inline mt-2 mb-0',
                    'onsubmit' => "return confirm(" . json_encode('Remove ' . $signUp->member->fullname . ' from this activity and give their place to the selected member?') . ")",
                ])
            !!}
                <label class="sr-only" for="replacement_id_{{ $signUp->id }}">Replacement</label>
                <select class="form-control form-control-sm mr-2" name="replacement_id" id="replacement_id_{{ $signUp->id }}">
                    @foreach ($activity->waiting_list_sign_ups as $waiting)
                        <option value="{{ $waiting->id }}">
                            {{ $loop->iteration }}. {{ $waiting->member->fullname }}@if ($waiting->plus_ones > 0) (+{{ $waiting->plus_ones }})@endif
                        </option>
                    @endforeach
                </select>
                <button class="btn btn-sm btn-outline-primary" type="submit">Replace</button>
            {!! Form::close() !!}
            <small class="form-text text-muted">
                {{ $signUp->member->fullname }} is removed from the activity, and the selected member gets their place right away.
            </small>
        </details>
    @endif

    @if ($signUp->discount !== 0)
        <strong>Discount</strong>
        : <i class="fas fa-euro-sign text-muted ml-1" title="Discount"></i>
        {{ number_format($signUp->discount / 100, 2) }}
    @endif
    @if ($signUp->dietary_wishes !== '')
        <p class="mb-0 mt-2">
            <i class="fas fa-utensils fa-fw text-muted" title="Dietary wishes"></i>
            {{ $signUp->dietary_wishes }}
        </p>
    @endif
</li>
