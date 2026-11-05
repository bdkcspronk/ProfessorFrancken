@extends('admin.layout')
@section('page-title', 'Deductions')

@php
use Francken\Treasurer\Http\Controllers\DeductionsController;
@endphp

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <table class="table table-hover mb-0 treasurer-table">
                    <thead>
                        <tr>
                            <th>Deduction date</th>
                            <th>Emails</th>
                            <th class="text-right">Members</th>
                            <th class="text-right">Total amount</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($deductions as $deduction)
                            @include('admin.treasurer.deductions._deduction-row', ['deduction' => $deduction])
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    No deductions yet. Upload the first one with the form on the right.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($deductions->hasPages())
                    <div class="card-footer">
                        {{ $deductions->links() }}
                    </div>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            @include('admin.treasurer.deductions._create', ['deduction' => $deduction])
        </div>
    </div>
@endsection
