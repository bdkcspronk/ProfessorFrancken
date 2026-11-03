@extends('layout.one-column-layout')

@section('page-title')
    Reset password
@endsection
@section('title', "Forgotten password - T.F.V. ‘Professor Francken’")

@section('content')
    <div class="login-prompt auth-box">
        <h2 class="login-prompt__title"><i class="fas fa-unlock-alt" aria-hidden="true"></i> Forgot your password?</h2>
        <p>Fill in your email address, and we will send you a link to choose a new password.</p>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <x-forms.email
                name="email"
                label="Email"
                help="Don't know your email? Contact the board at secretary@professorfrancken.nl."
                placeholder="mail@example.com"
                value="{{ old('email') }}"
                required
                autofocus
            />

            <div class="auth-box__actions">
                <button type="submit" class="btn home-btn home-btn--crimson">
                    Send password reset link
                </button>
                <a class="home-link" href="{{ route('login') }}">Back to login</a>
            </div>
        </form>
    </div>
@endsection
