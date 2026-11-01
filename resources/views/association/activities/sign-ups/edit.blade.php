@extends('layout.two-column-layout')
@section('title', 'Change sign up for ' . $activity->name . " - T.F.V. ‘Professor Francken’")

    @section('content')
        <div class="rounded shadow-sm">
            <div class="bg-white p-4 ">
                {!!
                       Form::model($signUp, [
                           'url' => action(
                               [\Francken\Association\Activities\Http\SignUpsController::class, 'update'],
                               ['activity' => $activity, 'sign_up' => $signUp]
                           ),
                           'method' => 'PUT'
                       ])
                !!}
                @include('association.activities.sign-ups._form', ['activity' => $activity, 'signUp' => $signUp])


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
                               $signUp,
                               [
                                   'url' => action(
                                       [\Francken\Association\Activities\Http\SignUpsController::class, 'destroy'],
                                       ['activity' => $activity, 'sign_up' => $signUp]
                                   ),
                                   'method' => 'post'
                               ]
                           )
                    !!}
                    @method('DELETE')
                    <div>
                        <button class="btn btn-text px-1">
                            Cancel your sign up
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
