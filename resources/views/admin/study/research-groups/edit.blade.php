@extends('admin.layout')
@section('page-title', 'Research groups / ' . $group->title . ' / Edit')

@section('content')
    <div class="card">
        <div class="card-body bg-light">
            {!!
                   Form::model($group, [
                       'url' => action([\Francken\Study\ResearchGroups\Http\AdminResearchGroupsController::class, 'update'], ['researchGroup' => $group]),
                       'method' => 'PUT',
                       'enctype' => 'multipart/form-data'
                   ])
            !!}
                @include('admin.study.research-groups._form', ['group' => $group])

                <x-forms.submit>Save</x-forms.submit>
            {!! Form::close() !!}
        </div>
    </div>

    {!!
           Form::model($group, [
               'url' => action([\Francken\Study\ResearchGroups\Http\AdminResearchGroupsController::class, 'destroy'], ['researchGroup' => $group]),
               'method' => 'post'
           ])
    !!}
    @method('DELETE')
    <p class="mt-2 text-muted d-flex align-items-center justify-content-end">
        Click <button
                  class="btn btn-text px-1"
                  onclick='return confirm("Are you sure you want to remove this research group and its groups?");'
              >here</button> to remove this research group.
    </p>
    {!! Form::close() !!}
@endsection
