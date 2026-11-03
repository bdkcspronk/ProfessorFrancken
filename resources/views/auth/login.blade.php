@extends('layout.one-column-layout')

@section('page-title')
    Login
@endsection
@section('title', "Login - T.F.V. ‘Professor Francken’")

@section('content')
    <div class="login-prompt auth-box">
        <h2 class="login-prompt__title"><i class="fas fa-user" aria-hidden="true"></i> Log in to your account</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email"
                       type="email"
                       class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                       name="email"
                       value="{{ old('email') }}"
                       placeholder="mail@example.com"
                       required
                       autofocus
                >
                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password"
                       type="password"
                       class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                       name="password"
                       placeholder="Your password"
                       required
                >
                @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    {{ __('Remember Me') }}
                </label>
            </div>

            <div class="auth-box__actions">
                <button type="submit" class="btn home-btn home-btn--crimson">
                    Login
                </button>

                @if (Route::has('password.request'))
                    <a class="home-link" href="{{ route('password.request') }}">
                        Forgot your password?
                    </a>
                @endif
            </div>
        </form>

        <p class="auth-box__footer">
            Not a member yet? <a href="/register">Become a member</a>
        </p>
    </div>
@endsection
