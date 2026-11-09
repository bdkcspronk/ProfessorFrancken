@extends('admin.layout')
@section('page-title', 'Research groups / ' . $group->title . ' / ' . $unit->name . ' group')

@section('content')
    <div class="card">
        <div class="card-body bg-light">
            {!!
                   Form::model($unit, [
                       'url' => action([\Francken\Study\ResearchGroups\Http\AdminResearchUnitsController::class, 'update'], ['researchGroup' => $group, 'unit' => $unit]),
                       'method' => 'PUT',
                       'enctype' => 'multipart/form-data'
                   ])
            !!}
                @include('admin.study.research-groups.units._form', ['unit' => $unit])

                <x-forms.submit>Save</x-forms.submit>
            {!! Form::close() !!}
        </div>
    </div>

    {!!
           Form::model($unit, [
               'url' => action([\Francken\Study\ResearchGroups\Http\AdminResearchUnitsController::class, 'destroy'], ['researchGroup' => $group, 'unit' => $unit]),
               'method' => 'post'
           ])
    !!}
    @method('DELETE')
    <p class="mt-2 text-muted d-flex align-items-center justify-content-end">
        Click <button
                  class="btn btn-text px-1"
                  onclick='return confirm("Are you sure you want to remove this group?");'
              >here</button> to remove this group.
    </p>
    {!! Form::close() !!}
@endsection
