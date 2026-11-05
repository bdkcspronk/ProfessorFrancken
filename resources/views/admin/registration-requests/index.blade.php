@extends('admin.layout')
@section('page-title', 'Registration requests')

@section('content')
    <div class="card">
        <div class="card-header p-0">
            <ul class="nav nav-tabs card-header-tabs m-0">
                @component('admin.registration-requests._tab-navigation', ['request' => $request, 'select' => 'open', 'class' => 'border-left-0'])
                    Open requests
                    <span class="badge badge-secondary text-white">
                        {{ $open_requests  }}
                    </span>
                @endcomponent
                @component('admin.registration-requests._tab-navigation', ['request' => $request, 'select' => 'approved-not-signed'])
                    Approved but not signed
                    <span class="badge badge-secondary text-white">
                        {{ $approved_not_signed_requests  }}
                    </span>
                @endcomponent
                @component('admin.registration-requests._tab-navigation', ['request' => $request, 'select' => 'approved'])
                    Approved requests
                    <span class="badge badge-secondary text-white">
                        {{ $approved_requests  }}
                    </span>
                @endcomponent
                @component('admin.registration-requests._tab-navigation', ['request' => $request, 'select' => 'all'])
                    All
                    <span class="badge badge-secondary text-white">
                        {{ $all_requests  }}
                    </span>
                @endcomponent
            </ul>
        </div>

        <p class="card-body text-muted small mb-0">
            Inspect a request to complete the registration, or mark it as spam to delete it.
        </p>
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th class="text-right">Submitted at</th>
                    <th class="text-right">Form signed at</th>
                    <th class="text-right">Approved at</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($requests as $registration)
                    @php
                        $url = action([\Francken\Association\Members\Http\Controllers\Admin\RegistrationRequestsController::class, 'show'], ['registration' => $registration]);
                    @endphp
                    <tr>
                        <th scope="row">
                            <a href="{{ $url }}">
                                <div>
                                    {{ $registration->fullname->toString() }}
                                </div>
                                <small>
                                    {{ $registration->email->toString() }}
                                    @if ($registration->email_verified_at === null)
                                        <small class="text-danger font-weight-bold">(not yet verified)</small>
                                    @endif
                                </small>
                            </a>
                        </th>
                        <td class="text-right">
                            {{ $registration->created_at->format('Y-m-d') }}
                        </td>
                        <td class="text-right">
                            {{ optional($registration->registration_form_signed_at)->format('Y-m-d') ?? '–' }}
                        </td>
                        <td class="text-right">
                            {{ optional($registration->registration_accepted_at)->format('Y-m-d') ?? '–' }}
                        </td>
                        <td class="text-right">
                            <a class="btn btn-sm btn-outline-primary" href="{{ $url }}">
                                <i class="fa fa-search" aria-hidden="true"></i>
                                Inspect
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            There are no registration requests here.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if ($requests->hasPages())
            <div class="card-footer">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
@endsection

@section('actions')
    <div class="d-flex align-items-start">
        <a href="{{ action([\Francken\Association\Members\Http\Controllers\RegistrationController::class, 'index']) }}"
            class="btn btn-primary mr-3"
        >
            <i class="fas fa-plus"></i>
            Add registration
        </a>
    </div>
@endsection
