@extends('layout.two-column-layout')
@section('title', 'Edit comment at ' . $activity->name . " - T.F.V. 'Professor Francken'")

@section('content')
    <div class="rounded shadow-sm">
        <div class="bg-white p-4 ">
            {!!
                   Form::model($comment, [
                       'url' => action(
                           [\Francken\Association\Activities\Http\CommentsController::class, 'update'],
                           ['activity' => $activity, 'comment' => $comment]
                       ),
                       'method' => 'PUT'
                   ])
            !!}

            <x-forms.textarea name="content" label="Comment" :rows="2" />

            <div class="d-flex justify-content-between mt-4">
                <div>
                    <button class="btn btn-primary mr-3" type="submit">
                        Save
                    </button>
                </div>
            </div>

            {!! Form::close() !!}
            <div class="">
                {!!
                       Form::model(
                           $comment,
                           [
                               'url' => action(
                                   [\Francken\Association\Activities\Http\CommentsController::class, 'destroy'],
                                   ['activity' => $activity, 'comment' => $comment]
                               ),
                               'method' => 'post'
                           ]
                       )
                !!}
                @method('DELETE')
                <div>
                    <button class="btn btn-text px-1">
                        Remove comment
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection

@section('aside')
    @include('association.activities._calendar')
@endsection
