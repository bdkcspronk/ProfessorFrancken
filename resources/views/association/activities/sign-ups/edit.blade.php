@extends('layout.two-column-layout')

@section('page-title')
    Change sign up
@endsection
@section('title', 'Change sign up for ' . $activity->name . " - T.F.V. ‘Professor Francken’")

@section('content')
    @php
        $activityUrl = action([\Francken\Association\Activities\Http\ActivitiesController::class, 'show'], ['activity' => $activity]);
    @endphp

    <section class="profile-panel profile-form">
        <header class="profile-panel__header">
            <h2 class="profile-panel__title">
                <i class="fas fa-calendar-check" aria-hidden="true"></i>
                {{ $activity->name }}
            </h2>
        </header>
        @if ($signUp->member !== null)
            <p>Sign up of <strong>{{ $signUp->member->fullname }}</strong></p>
        @endif

        {!!
               Form::model($signUp, [
                   'url' => action(
                       [\Francken\Association\Activities\Http\SignUpsController::class, 'update'],
                       ['activity' => $activity, 'sign_up' => $signUp]
                   ),
                   'method' => 'PUT'
               ])
        !!}
            @include('association.activities.sign-ups._form', ['activity' => $activity, 'signUp' => $signUp, 'showHeader' => false])

            <div class="profile-form__actions">
                <button class="btn home-btn home-btn--crimson" type="submit">Save</button>
                <a href="{{ $activityUrl }}" class="profile-form__cancel">Back to the activity</a>
            </div>
        {!! Form::close() !!}
    </section>

    <section class="profile-panel profile-panel--note">
        <h3 class="profile-form__heading mt-0">Can't come after all?</h3>
        <p>Cancel your sign up, so that someone else can take your place.</p>
        {!!
               Form::model(
                   $signUp,
                   [
                       'url' => action(
                           [\Francken\Association\Activities\Http\SignUpsController::class, 'destroy'],
                           ['activity' => $activity, 'sign_up' => $signUp]
                       ),
                       'method' => 'post',
                       'onsubmit' => "return confirm('Are you sure you want to cancel this sign up?')",
                   ]
               )
        !!}
            @method('DELETE')
            <button class="btn danger-button" type="submit">
                <i class="fas fa-times" aria-hidden="true"></i>
                Cancel your sign up
            </button>
        {!! Form::close() !!}
    </section>
@endsection

@section('aside')
    @include('association.activities._facts')
@endsection
