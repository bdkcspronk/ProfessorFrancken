@extends('layout.one-column-layout')

@section('page-title')
    Reset password
@endsection
@section('title', "Reset password - T.F.V. ‘Professor Francken’")

@section('content')
    <div class="login-prompt auth-box">
        <h2 class="login-prompt__title"><i class="fas fa-unlock-alt" aria-hidden="true"></i> Choose a new password</h2>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label for="email">Email</label>

                <input id="email"
                       type="email"
                       class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                       name="email"
                       placeholder="mail@example.com"
                       value="{{ $email ?? old('email') }}"
                       required
                       autofocus
                >

                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <p class="mb-1">
                            <strong>{{ $errors->first('email') }}</strong>
                        </p>
                        @if ($errors->first('email') === trans('passwords.token'))
                        <p>
                            Your token may have expired. For security reasons tokens are valid for about 60 minutes after they've been created,
                            <a
                                href="{{ action([Francken\Auth\Http\Controllers\ForgotPasswordController::class, 'showLinkRequestForm']) }}"
                                >
                                click here
                            </a> to send a new reset password token to your email.
                        </p>
                        @endif
                    </span>
                @endif
            </div>
            <div class="form-group">
                <label for="password">New password</label>

                <input id="password"
                       type="password"
                       class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                       name="password"
                       required
                >

                @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <p>
                            <strong>{{ $errors->first('password') }}</strong>
                        </p>
                    </span>
                @endif
            </div>
            <div class="form-group">
                <label for="password-confirm">
                    Confirm new password
                </label>

                <input id="password-confirm"
                       type="password"
                       class="form-control"
                       name="password_confirmation"
                       required
                >
            </div>

            <div class="auth-box__actions">
                <button type="submit" class="btn home-btn home-btn--crimson">
                    Reset password
                </button>
            </div>
        </form>
    </div>
@endsection
