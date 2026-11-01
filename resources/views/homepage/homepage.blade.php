@extends('homepage.layout')

@section('title', "T.F.V. 'Professor Francken'")
@section('description', "‘Professor Francken’ is the study association for Engineering Physics, connected to the University of Groningen. It is named after Groningen’s first professor of Applied Physics and is for students and staff of the applied physics departments.")

@section('main-content')
    @include("homepage._hero")

    @include("homepage._agenda")

    @include("homepage._about-francken")

    @include("homepage._membership")

    @include("homepage._pillars")
@endsection
