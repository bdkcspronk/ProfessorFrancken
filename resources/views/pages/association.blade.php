@extends('layout.one-column-layout')

@section('page-title')
    Association
@endsection
@section('title', "Association - T.F.V. ‘Professor Francken’")
@section('description', "The activities, news, committees and magazine of T.F.V. ‘Professor Francken’.")

@section('content')
    <div class="page-intro">
        <p>All year round Francken organizes activities, from lectures and excursions to borrels and parties, most of them run by our members in committees. Here you will find what is coming up, the latest news, our committees and the Francken Vrij.</p>
    </div>

    @include('layout._menu-cards', ['menu' => '/association'])
@endsection
