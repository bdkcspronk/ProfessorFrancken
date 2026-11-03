@extends('profile.layout')

@section('content')
    <section class="profile-panel profile-form">
        <header class="profile-panel__header">
            <h2 class="profile-panel__title">
                <i class="fas fa-key" aria-hidden="true"></i>
                Change password
            </h2>
        </header>

        {!!
               Form::open([
                   'url' => action([\Francken\Association\Members\Http\PasswordController::class, 'update']),
               ])
        !!}
            @method('PUT')

            <div class="form-group">
                <label for="current_password">Current password</label>
                {!!
                       Form::password(
                           'current_password',
                           [
                               'class' => 'form-control' . ($errors->has('current_password') ? ' is-invalid' : ''),
                               'id' => 'current_password',
                               'required' => 'required',
                           ]
                       )
                !!}
                @error('current_password')
                <p class="invalid-feedback">
                    {{ $message  }}
                </p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">New password</label>
                {!!
                       Form::password(
                           'password',
                           [
                               'class' => 'form-control' . ($errors->has('password') ? ' is-invalid' : ''),
                               'placeholder' => '',
                               'id' => 'password',
                               'required' => 'required',
                               'minlength' => 8,
                           ]
                       )
                !!}
                @error('password')
                <p class="invalid-feedback">
                    {{ $message  }}
                </p>
                @else
                <small class="form-text text-muted">
                    Passwords must be at least 8 characters long.
                </small>
                @enderror
            </div>

            <div class="profile-form__actions">
                <x-forms.submit class="btn home-btn home-btn--crimson">Change password</x-forms.submit>
                <a href="{{ route('password.request') }}" class="profile-form__cancel">Forgot your password?</a>
            </div>
        {!! Form::close() !!}
    </section>
@endsection
