@extends('layout.one-column-layout')

@section('page-title')
    Boards of T.F.V. ‘Professor Francken’
@endsection

@section('main-content')
    <div class="container my-5">
    </div>

    @foreach ($boards as $board)
        @include("association.boards._board", ['board' => $board])
    @endforeach

    @guest
    <div class="container mt-5">
        <x-login-prompt title="Login to view older boards">
        </x-login-prompt>
    </div>
    @endguest
@endsection
