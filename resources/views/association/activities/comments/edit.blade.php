@extends('layout.two-column-layout')

@section('page-title')
    Edit comment
@endsection
@section('title', 'Edit comment at ' . $activity->name . " - T.F.V. ‘Professor Francken’")

@section('content')
    @php
        $activityUrl = action([\Francken\Association\Activities\Http\ActivitiesController::class, 'show'], ['activity' => $activity]);
    @endphp

    <section class="profile-panel profile-form">
        <header class="profile-panel__header">
            <h2 class="profile-panel__title">
                <i class="fas fa-comment" aria-hidden="true"></i>
                {{ $activity->name }}
            </h2>
        </header>

        {!!
               Form::model($comment, [
                   'url' => action(
                       [\Francken\Association\Activities\Http\CommentsController::class, 'update'],
                       ['activity' => $activity, 'comment' => $comment]
                   ),
                   'method' => 'PUT'
               ])
        !!}
            <x-forms.textarea name="content" label="Comment" :rows="4" />

            <div class="profile-form__actions">
                <button class="btn home-btn home-btn--crimson" type="submit">Save</button>
                <a href="{{ $activityUrl }}" class="profile-form__cancel">Back to the activity</a>
            </div>
        {!! Form::close() !!}
    </section>

    <section class="profile-panel profile-panel--note">
        {!!
               Form::model(
                   $comment,
                   [
                       'url' => action(
                           [\Francken\Association\Activities\Http\CommentsController::class, 'destroy'],
                           ['activity' => $activity, 'comment' => $comment]
                       ),
                       'method' => 'post',
                       'onsubmit' => "return confirm('Are you sure you want to remove this comment?')",
                   ]
               )
        !!}
            @method('DELETE')
            <button class="btn danger-button" type="submit">
                <i class="fas fa-trash-alt" aria-hidden="true"></i>
                Remove comment
            </button>
        {!! Form::close() !!}
    </section>
@endsection

@section('aside')
    @include('association.activities._facts')
@endsection
