@extends('admin.layout')
@section('page-title', 'Pages')

@section('content')
    <p class="text-muted small">
        Pages of the website with their own text. Click a page to see or edit it.
    </p>
    <div class="card">
        <table class="table table-hover admin-clickable-rows mb-0">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Address on the website</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pages as $page)
                    <tr>
                        <td>
                            <a class="admin-row-link font-weight-bold" href="{{ action([\Francken\Shared\Http\Controllers\Admin\PagesController::class, 'show'], ['page' => $page]) }}">
                                {{ $page->title }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ url($page->slug) }}" target="_blank" rel="noopener" class="text-muted">
                                /{{ $page->slug }}
                                <i class="fas fa-external-link-alt fa-xs" aria-hidden="true"></i>
                                <span class="sr-only">(opens in a new tab)</span>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center text-muted py-4">There are no custom pages yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@section('actions')
    <div class="d-flex align-items-start">
        <a href="{{ action([\Francken\Shared\Http\Controllers\Admin\PagesController::class, 'create']) }}"
            class="btn btn-primary"
        >
            <i class="fas fa-plus"></i>
            Add page
        </a>
    </div>
@endsection
