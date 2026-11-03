@extends('layout.one-column-layout')

@section('page-title')
    Hi {{ $registration->fullname->toString() }}, thank you for registering!
@endsection

@section('content')
    @if ($registration->email_verified_at === null)
        <div class="album-notice register-notice" role="status">
            <i class="fas fa-envelope" aria-hidden="true"></i>
            We have sent an email to <a href="mailto:{{ $registration->email->toString() }}">{{ $registration->email->toString() }}</a>.
            Please confirm your email address with the link in that email.
        </div>
    @endif

    <div class="row">
        <div class="col-lg-7">
            <div class="registration-summary">
                <h2 class="profile-panel__title mb-3">
                    <i class="fas fa-clipboard-list" aria-hidden="true"></i>
                    Your registration
                </h2>
                @include('registration.show._personal-details', ['registration' => $registration])
                @include('registration.show._contact-details', ['registration' => $registration])
                @include('registration.show._study-details', ['registration' => $registration])
                @include('registration.show._billing-details', ['registration' => $registration])
            </div>
        </div>

        <div class="col-lg-5">
            <div class="register-next">
                <h2 class="home-heading">What happens next?</h2>
                <ol class="register-next__steps">
                    <li @class(['is-done' => $registration->email_verified_at !== null])>
                        <strong>Confirm your email address</strong>
                        with the link in the email we sent you.
                    </li>
                    <li>
                        <strong>A board member reviews your registration</strong>
                        and lets you know when you are a member.
                    </li>
                    <li>
                        <strong>Sign the form of consent</strong>
                        for the membership fee and your expenses, the next time you visit the Francken room.
                    </li>
                </ol>
                <p class="mb-0">
                    Is something missing or not right? Let us know at <a href="mailto:board@professorfrancken.nl">board@professorfrancken.nl</a>.
                </p>
            </div>
        </div>
    </div>
@endsection
