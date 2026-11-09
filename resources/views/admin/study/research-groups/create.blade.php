@extends('admin.layout')
@section('page-title', 'Research groups / Add research group')

@section('content')
    <div class="card">
        <div class="card-body bg-light">
            {!!
                   Form::model($group, [
                       'url' => action([\Francken\Study\ResearchGroups\Http\AdminResearchGroupsController::class, 'store']),
                       'method' => 'POST',
                       'enctype' => 'multipart/form-data'
                   ])
            !!}
                @include('admin.study.research-groups._form', ['group' => $group])

                <x-forms.submit>Add research group</x-forms.submit>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
