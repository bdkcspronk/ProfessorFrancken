@extends('admin.layout')
@section('page-title', 'Research groups / Introduction')

@section('content')
    <div class="card">
        <div class="card-body">
            {!!
                   Form::model($introduction, [
                       'url' => action([\Francken\Study\ResearchGroups\Http\AdminResearchGroupsController::class, 'updateIntroduction']),
                       'method' => 'PUT',
                   ])
            !!}
                <x-forms.markdown label="Introduction" />

                <x-forms.submit>Save</x-forms.submit>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
