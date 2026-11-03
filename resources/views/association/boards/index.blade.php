@extends('layout.one-column-layout')

@section('page-title')
    Boards of T.F.V. ‘Professor Francken’
@endsection

@section('main-content')
    <div class="container my-5">
        @foreach ($boards as $board)
            @include("association.boards._board", ['board' => $board])
        @endforeach

        @guest
            <x-login-prompt title="Login to view older boards">
            </x-login-prompt>
        @endguest
    </div>
@endsection
