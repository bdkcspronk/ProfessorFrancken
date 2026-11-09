@extends('admin.layout')
@section('page-title', 'Research groups')

@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
                <h4 class="font-weight-bold">Introduction</h4>
                <a href="{{ action([\Francken\Study\ResearchGroups\Http\AdminResearchGroupsController::class, 'editIntroduction']) }}"
                   class="btn btn-sm btn-outline-primary"
                >
                    <i class="fas fa-edit"></i>
                    Edit introduction
                </a>
            </div>
            <p class="text-muted small">
                Shown above the research groups on the <a href="/study/research-groups" target="_blank" rel="noopener">research groups page</a>.
            </p>
            @if ($introduction->compiled_content)
                {!! $introduction->compiled_content !!}
            @else
                <p class="text-muted font-weight-light mb-0">There is no introduction yet.</p>
            @endif
        </div>
    </div>

    <p class="text-muted small">
        Click a research group to see or edit it and its groups.
    </p>
    <div class="card">
        <table class="table table-hover admin-clickable-rows mb-0">
            <thead>
                <tr>
                    <th></th>
                    <th>Research group</th>
                    <th>Groups</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($groups as $group)
                    <tr>
                        <td style="width: 100px">
                            @if ($group->photo)
                                <img
                                    src="{{ image($group->photo, ['width' => 160, 'height' => 100]) }}"
                                    alt=""
                                    class="rounded"
                                    style="width: 80px; height: 50px; object-fit: cover"
                                >
                            @endif
                        </td>
                        <td class="align-middle">
                            <a class="admin-row-link font-weight-bold" href="{{ action([\Francken\Study\ResearchGroups\Http\AdminResearchGroupsController::class, 'show'], ['researchGroup' => $group]) }}">
                                {{ $group->title }}
                            </a>
                        </td>
                        <td class="align-middle text-muted">
                            {{ $group->units_count }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">There are no research groups yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@section('actions')
    <div class="d-flex align-items-start">
        <a href="{{ action([\Francken\Study\ResearchGroups\Http\AdminResearchGroupsController::class, 'create']) }}"
            class="btn btn-primary"
        >
            <i class="fas fa-plus"></i>
            Add research group
        </a>
    </div>
@endsection
