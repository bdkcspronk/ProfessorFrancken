@extends('admin.layout')
@section('page-title', 'Symposia')

@section('content')
    <div class="card">
        @include('admin.association.symposia._table', ['symposia' => $symposia])

        @if (count($symposia) === 0)
            <div class="card-body text-center text-muted py-5">
                <p class="mb-2">There are no symposia yet.</p>
                <p class="small mb-0">Start one with "Start new symposium" above, to manage its participants and their attendance.</p>
            </div>
        @endif

        @if ($symposia->hasPages())
            <div class="card-footer">
                {!! $symposia->links() !!}
            </div>
        @endif
    </div>
@endsection

@section('actions')
    <div class="d-flex align-items-end">
        <a href="{{ action([\Francken\Association\Symposium\Http\AdminSymposiaController::class, 'create']) }}"
            class="btn btn-primary"
        >
            <i class="fas fa-plus"></i>
            Start new symposium
        </a>
    </div>
@endsection
