@extends('layout.one-column-layout')

@section('page-title')
    Study
@endsection
@section('title', "Study - T.F.V. ‘Professor Francken’")
@section('description', "As a study association T.F.V. ‘Professor Francken’ organizes a plethora of activities related to (applied) physics, including symposia, (research group) excursions, practice sessions for exams.")

@section('content')
    <div class="page-intro">
        <p>As a study association, Francken helps you get through your studies: we organize symposia, excursions to research groups and practice sessions for exams, and sell second-hand study books. We also stay in touch with the programme and the councils of the faculty and the university, so that the voice of engineering physics students is heard.</p>
    </div>

    @include('layout._menu-cards', ['menu' => '/study'])
@endsection
