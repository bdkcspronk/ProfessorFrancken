@extends('layout.one-column-layout')

@section('page-title')
    Association
@endsection
@section('title', "Association - T.F.V. ‘Professor Francken’")
@section('description', "The activities, news, committees, magazine and photos of T.F.V. ‘Professor Francken’.")

@section('content')
    <div class="page-intro">
        <p>All year round Francken organizes activities, from lectures and excursions to borrels and parties, most of them run by our members in committees. Here you will find what is coming up, the latest news, our committees, the Francken Vrij and the photos of what we did.</p>
    </div>

    @include('layout._menu-cards', ['menu' => '/association'])
@endsection
