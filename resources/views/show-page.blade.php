@extends('layout.one-column-layout')
@section('title', $page->title . " - T.F.V. ‘Professor Francken’")

@section('content')
    {{-- The content is written in the admin, so it is styled by its elements --}}
    <div class="cms-page">
        {!!
               $page->compiled_content
        !!}
    </div>
    <p class="cms-page__updated">
        Last updated {{ $page->updated_at->diffForHumans() }}
        @can('dashboard:pages-write')
            <a href="{{ action([\Francken\Shared\Http\Controllers\Admin\PagesController::class, 'edit'], ['page' => $page]) }}" class="home-link ml-2">
                <i class="fas fa-edit" aria-hidden="true"></i> Edit
            </a>
        @endcan
    </p>
@endsection
