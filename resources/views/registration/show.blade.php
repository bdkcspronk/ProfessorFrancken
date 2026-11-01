@extends('layout.one-column-layout')

@section('page-title')
    Hi {{ $registration->fullname->toString() }}, thank you for registering!
@endsection

@section('content')
    @if ($registration->email_verified_at === null)
        <div class="alert alert-warning my-5">
            We have sent you a confirmation email to <a href="malto: {{ $registration->email->toString() }}">{{ $registration->email->toString() }}</a>.
            Please verify your email by clicking the link in the email.
        </div>
    @endif

    <p class="lead text-center">
        Your registration is being reviewed by one of our board members.
        Below your registration details are shown, if something is missing or incorrect please let us know by emailing to <a href="malto: board@professorfrancken.nl">board@professorfrancken.nl</a>.
    </p>

    <div class="bg-white p-4 rounded text-primary mt-5">
        @include('registration.show._personal-details', ['registration' => $registration])
        @include('registration.show._contact-details', ['registration' => $registration])
        @include('registration.show._study-details', ['registration' => $registration])
        @include('registration.show._billing-details', ['registration' => $registration])
        @if (1 == 2)
        @include('registration.show._other-details', ['registration' => $registration])
        @endif
    </div>
@endsection
