@props([
    'title' => 'Login',
])

<div class="login-prompt">
    <h3 class="login-prompt__title"><i class="fas fa-lock" aria-hidden="true"></i> {{ $title }}</h3>

    {{ $slot }}

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <x-forms.email name="email" label="Email" placeholder="member@example.com" help="Use the same email address on which you receive our newsletters."/>
        <x-forms.password name="password" label="Password" />
        <x-forms.checkbox name="remember" :label="__('Remember Me')"/>

        <div class="form-group mb-0">
            <button type="submit" class="btn home-btn home-btn--crimson">
                Login
            </button>

            @if (Route::has('password.request'))
                <a class="home-link ml-3" href="{{ route('password.request') }}">
                    Forgot Your Password?
                </a>
            @endif
        </div>
    </form>
</div>
