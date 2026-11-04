@extends('layout.one-column-layout')

@section('page-title')
    Boards of T.F.V. ‘Professor Francken’
@endsection

@push('scripts')
    <script src="/js/BoardPhotos.js?v={{ filemtime(public_path('js/BoardPhotos.js')) }}"></script>
@endpush

@section('main-content')
    <div class="container my-5" data-board-photos>
        @foreach ($boards as $board)
            @include("association.boards._board", ['board' => $board, 'first' => $loop->first])
        @endforeach

        @guest
            <x-login-prompt title="Login to view older boards">
            </x-login-prompt>
        @endguest
    </div>
@endsection
