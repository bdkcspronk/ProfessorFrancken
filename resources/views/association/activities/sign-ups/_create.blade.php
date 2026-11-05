{!!
       Form::open([
           'url' => action(
               [\Francken\Association\Activities\Http\SignUpsController::class, 'store'],
               ['activity' => $activity]
           ),
       ])
!!}
@if ($activity->is_full)
    <p class="activity-page__full">
        <i class="fas fa-hourglass-half" aria-hidden="true"></i>
        This activity is full, but you can still sign up for the waiting list.
        When someone cancels, the first ones on the waiting list get their place.
    </p>
@endif

@include('association.activities.sign-ups._form', ['activity' => $activity, 'isNewSignUp' => true])

<p class="mt-3">
    <button class="btn home-btn home-btn--crimson mr-3" type="submit">
        {{ $activity->is_full ? 'Sign up for the waiting list' : 'Sign up' }}
    </button>
    The sign up deadline ends in {{ $activity->registration_deadline->diffForHumans() }}
</p>

{!! Form::close() !!}
