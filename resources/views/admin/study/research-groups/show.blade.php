@extends('admin.layout')
@section('page-title', 'Research groups / ' . $group->title)

@section('content')
    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    @if ($group->photo)
                        <img
                            class="img-fluid rounded mb-3"
                            src="{{ image($group->photo, ['width' => 1000, 'height' => 400]) }}"
                            alt="Photo of {{ $group->title }}"
                        >
                    @endif

                    <div class="p-3 bg-light mb-4">
                        <h5 class="h6">Description</h5>
                        @if ($group->compiled_content)
                            {!! $group->compiled_content !!}
                        @else
                            <p class="text-muted font-weight-light mb-0">
                                Edit this research group to describe what it works on.
                            </p>
                        @endif
                    </div>

                    <h4 class="font-weight-bold">
                        <i class="fas fa-users"></i>
                        Groups
                    </h4>
                    <p>
                        The groups within this research group, each led by a professor.
                        Edit a group to change its details or photo, or to remove it.
                    </p>

                    <ul class="list-unstyled mb-5">
                        @forelse ($group->units as $unit)
                            <li class="p-2 my-2 bg-light d-flex align-items-center">
                                @if ($unit->photo)
                                    <img
                                        src="{{ image($unit->photo, ['width' => 96, 'height' => 96]) }}"
                                        alt=""
                                        class="rounded mr-3"
                                        style="width: 48px; height: 48px; object-fit: cover"
                                    >
                                @endif
                                <div class="d-flex flex-column">
                                    <span class="font-weight-bold">{{ $unit->name }} group</span>
                                    <small class="text-muted">{{ $unit->title }}</small>
                                </div>
                                <div class="ml-auto d-flex align-items-center">
                                    @if ($unit->website_url)
                                        <a class="btn btn-text btn-sm text-muted" href="{{ $unit->website_url }}" target="_blank" rel="noopener">Website</a>
                                    @endif
                                    @if ($unit->contact_url)
                                        <a class="btn btn-text btn-sm text-muted" href="{{ $unit->contact_url }}" target="_blank" rel="noopener">Contact</a>
                                    @endif
                                    <a
                                        class="btn btn-text text-primary btn-sm"
                                        href="{{ action([\Francken\Study\ResearchGroups\Http\AdminResearchUnitsController::class, 'edit'], ['researchGroup' => $group, 'unit' => $unit]) }}"
                                        title="Edit the {{ $unit->name }} group"
                                    >
                                        <i class="fas fa-edit"></i>
                                        <span class="sr-only">Edit the {{ $unit->name }} group</span>
                                    </a>
                                </div>
                            </li>
                        @empty
                            <li class="text-muted">This research group has no groups yet.</li>
                        @endforelse
                    </ul>

                    <h5 class="font-weight-bold">
                        <i class="fas fa-plus"></i>
                        Add group
                    </h5>
                    {!!
                           Form::open([
                               'url' => action([\Francken\Study\ResearchGroups\Http\AdminResearchUnitsController::class, 'store'], ['researchGroup' => $group]),
                               'method' => 'post',
                               'enctype' => 'multipart/form-data'
                           ])
                    !!}
                        @include('admin.study.research-groups.units._form', ['unit' => null])

                        <button class="btn btn-outline-primary">
                            <i class="fas fa-plus"></i>
                            Add group
                        </button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('actions')
    <div class="d-flex align-items-end">
        <a href="/study/research-groups/{{ $group->slug }}" class="btn btn-primary mx-3">
            <i class="fas fa-eye"></i>
            View page
        </a>
        <a href="{{ action([\Francken\Study\ResearchGroups\Http\AdminResearchGroupsController::class, 'edit'], ['researchGroup' => $group]) }}"
           class="btn btn-primary"
        >
            <i class="fas fa-edit"></i>
            Edit
        </a>
    </div>
@endsection
