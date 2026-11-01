@extends('layout.one-column-layout')

@section('page-title')
    <i class="fa fa-unlock-alt" aria-hidden="true"></i> Reset password
@endsection
@section('title', "Forgotten password - T.F.V. ‘Professor Francken’")

@section('content')
    <div class=" d-flex justify-content-center my-4">
        <div class="card p-5 mx-3 mx-md-5 w-100">
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="form-group">
                    <x-forms.email
                        name="email"
                        label="Email"
                        help="Don't know your email? Contact the board at secretary@professorfrancken.nl."
                        placeholder="mail@example.com"
                        value="{{ old('email') }}"
                        required
                        autofocus
                    />
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-lg btn-block btn-outline-primary">
                        Send password reset link
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
