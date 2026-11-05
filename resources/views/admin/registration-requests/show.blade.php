@extends('admin.layout')
@section('page-title', 'Registration requests / ' . $registration->fullname->toString())

{{-- TODO:

           Comments / questions:

           This person wants to join a committee, send them an email asking which!

           --}}
@section('content')
    <div class="card">
        <div class="card-body">
            <!-- <div class="bg-white p-4 rounded"> -->
            @include('registration.show._personal-details', ['registration' => $registration])
            @include('registration.show._contact-details', ['registration' => $registration])
            @include('registration.show._study-details', ['registration' => $registration])
            @include('registration.show._billing-details', ['registration' => $registration])

            <h5 class="mt-4 mx-2">
                <i class="fa fa-users" aria-hidden="true"></i>
                Active member
            </h5>

            <p>
                @if ($registration->wants_to_join_a_committee)
                    <i class="far fa-check-square"></i>
                    Is interested in joining a committee
                @else
                    <i class="far fa-square"></i>
                    Is not interested in joining a committee
                @endif
            </p>

            @if ($registration->comments !== '')
                <h6>Comments</h6>
                <p class="bg-light p-3">
                    {{ $registration->comments }}
                </p>
            @endif
        </div>

        <div class="card-footer">
                <h4>
                    <i class="fas fa-signature"></i>
                    Sign registration form
                </h4>
            @if ($registration->registration_form_signed_at === null)
                <p>
                    Use this action to let our system know that the member has signed the printed registration form.
                </p>
                {!!
                   Form::model(
                       $registration,
                       [
                           'url' => action(
                               [\Francken\Association\Members\Http\Controllers\Admin\RegistrationRequestsController::class, 'sign'],
                               ['registration' => $registration->id]
                           ),
                           'method' => 'post'
                       ]
                   )
                !!}
                <button
                    type="submit"
                    class="btn btn-primary"
                    onClick='return confirm("Are you sure you want to approve this registration request?");'
                >
                    Sign registration form
                </button>

                {!! Form::close() !!}
            @else
                <p>
                    The registration form was signed {{ $registration->registration_form_signed_at->diffForHumans() }}
                </p>
            @endif
        </div>
        <div class="card-footer">
            @if ($registration->registration_accepted_at === null)
                <h4>
                    <i class="fas fa-check"></i>
                    Approve membership
                </h4>
                <p>
                    Once all information has been checked you can approve this person's membership.
                </p>
                {!!
                   Form::model(
                       $registration,
                       [
                           'url' => action(
                               [\Francken\Association\Members\Http\Controllers\Admin\RegistrationRequestsController::class, 'approve'],
                               ['registration' => $registration->id]
                           ),
                           'method' => 'post'
                       ]
                   )
                !!}
                <button
                    type="submit"
                    class="btn btn-primary"
                    onClick='return confirm("Are you sure you want to approve this registration request?");'
                >
                    Approve
                </button>

                {!! Form::close() !!}
            @endif
        </div>
    </div>

    {!!
       Form::model(
           $registration,
           [
               'url' => action(
                   [\Francken\Association\Members\Http\Controllers\Admin\RegistrationRequestsController::class, 'remove'],
                   ['registration' => $registration->id]
               ),
               'method' => 'post'
           ]
       )
    !!}
    @method('DELETE')

    <p class="mt-3 d-flex justify-content-end">
        <button
            class="btn btn-sm btn-outline-danger"
            onclick='return confirm("Are you sure you want to delete this registration request?");'
        >
            <i class="fas fa-trash-alt" aria-hidden="true"></i>
            Delete registration request
        </button>
    </p>

    {!! Form::close() !!}
@endsection

@section('actions')
    <div class="d-flex align-items-start">
        <a
            class="btn btn-outline-primary mr-3"
            href="{{ action(
                       [\Francken\Association\Members\Http\Controllers\Admin\RegistrationRequestsController::class, 'print'],
                       ['registration' => $registration->id]
                   ) }}"
            target="_blank"
            title="In Google Chrome, set the margins to None and turn on Background graphics"
        >
            <i class="fas fa-print"></i>
            Print
        </a>
        @if ($registration->registration_accepted_at === null)
        <a
            class="btn btn-outline-primary"
            href="{{ action(
                       [\Francken\Association\Members\Http\Controllers\Admin\RegistrationRequestsController::class, 'edit'],
                       ['registration' => $registration->id]
                   ) }}"
        >
            <i class="fas fa-user-edit"></i>
            Edit
        </a>
        @endif
    </div>
@endsection
