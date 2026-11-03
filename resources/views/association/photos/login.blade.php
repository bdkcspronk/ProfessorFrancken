@extends('layout.one-column-layout')

@section('page-title')
    Photos
@endsection
@section('title', "Photos - T.F.V. ‘Professor Francken’")

@section('content')
    @if (session('private-album-login') === false)
        <div class="album-notice album-notice--error" role="alert">
            <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
            That password is not right.
        </div>
    @endif

    <div class="login-prompt album-login">
        <h3 class="login-prompt__title"><i class="fas fa-lock" aria-hidden="true"></i> Log in to see the photos</h3>
        <p>
            Our photos can only be seen by members. Fill in the <strong>album password</strong> below, or <a class="home-link" href="{{ route('login') }}">log in with your account</a>.
            Don't know the password? Ask a board member.
        </p>
        @include('association.photos._login-form')
    </div>
@endsection
