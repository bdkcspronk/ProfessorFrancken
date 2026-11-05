{{-- A place opened up for a member on the waiting list: they can accept or decline it --}}
<div class="activity-page__offer">
    <h3 class="activity-page__offer-title">
        <i class="fas fa-star" aria-hidden="true"></i>
        A place has opened up for you!
    </h3>
    <p>
        Someone cancelled, so you're next from the waiting list.
        @if ($signUp->plus_ones > 0)
            There is room for you and your {{ $signUp->plus_ones }} {{ \Illuminate\Support\Str::plural('guest', $signUp->plus_ones) }}.
        @endif
        Your place is kept for you, but it's only official once you accept it.
    </p>
    <div class="activity-page__offer-actions">
        {!! Form::open(['url' => action([\Francken\Association\Activities\Http\SignUpsController::class, 'accept'], ['activity' => $activity, 'sign_up' => $signUp])]) !!}
            <button class="btn home-btn home-btn--crimson" type="submit">
                <i class="fas fa-check" aria-hidden="true"></i>
                Accept my place
            </button>
        {!! Form::close() !!}
        {!!
            Form::open([
                'url' => action([\Francken\Association\Activities\Http\SignUpsController::class, 'destroy'], ['activity' => $activity, 'sign_up' => $signUp]),
                'method' => 'DELETE',
                'onsubmit' => "return confirm('Are you sure? Your place goes to the next one on the waiting list.')",
            ])
        !!}
            <button class="btn btn-link activity-page__offer-decline" type="submit">
                I can't come anymore
            </button>
        {!! Form::close() !!}
    </div>
</div>
