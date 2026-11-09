@extends('admin.layout')
@section('page-title', 'Activities / ' . $activity->name)

@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <p>
                    <i class="fas fa-clock"></i>
                    {{ $activity->start_date->format('D j F Y, H:i') }}
                    to
                    {{ $activity->end_date->isSameDay($activity->start_date) ? $activity->end_date->format('H:i') : $activity->end_date->format('D j F Y, H:i') }}
                </p>

                <div>
                    {!! $activity->compiled_content !!}
                </div>
            </div>

            @include('admin.association.activities.sign-up-settings.index', ['activity' => $activity])
        </div>

        {!!
               Form::model(
                   $activity,
                   [
                       'url' => action(
                           [\Francken\Association\Activities\Http\AdminActivitiesController::class, 'destroy'],
                           ['activity' => $activity]
                       ),
                       'method' => 'post'
                   ]
               )
        !!}
        @method('DELETE')
        <p class="mt-3 d-flex justify-content-end">
            <button
                class="btn btn-sm btn-outline-danger"
                onclick='return confirm("Are you sure you want to delete this activity?");'
            >
                <i class="fas fa-trash-alt" aria-hidden="true"></i>
                Delete activity
            </button>
        </p>
        {!! Form::close() !!}
    </div>

    <div class="col-md-3">
        {{--
        @include('admin.association.activities._notes', ['activity' => $activity])
        @include('admin.association.activities._organizers', ['activity' => $activity])
        @include('admin.association.activities._news', ['activity' => $activity])
        @include('admin.association.activities._photo_album', ['activity' => $activity])
        --}}
        @include('admin.association.activities._location', ['activity' => $activity])

    </div>
</div>
@endsection

@section('actions')
    <div class="d-flex align-items-start">
        <a href="{{ action(
                        [\Francken\Association\Activities\Http\ActivitiesController::class, 'show'],
                        ['activity' => $activity]
                        ) }}"
            class="btn btn-primary mr-2"
        >
            <i class="fas fa-eye"></i>
            Show activity page
        </a>
        <a href="{{ action(
                        [\Francken\Association\Activities\Http\AdminActivitySignUpsExportController::class, 'index'],
                        ['activity' => $activity]
                        ) }}"
            class="btn btn-primary mx-2"
        >
            <i class="fas fa-file-export"></i>
            Export
        </a>
        <a href="{{ action(
                        [\Francken\Association\Activities\Http\AdminActivitiesController::class, 'edit'],
                        ['activity' => $activity]
                        ) }}"
            class="btn btn-primary"
        >
            <i class="fas fa-edit"></i>
            Edit
        </a>
    </div>
@endsection
