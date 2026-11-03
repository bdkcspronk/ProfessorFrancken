@extends('profile.layout')

@section('content')
    <h2 class="home-heading profile-heading">Expenses</h2>

    <div class="page-intro">
        <p>
            Here you find what you bought at Francken, per month.
            Only purchases from the streepsysteem (the fridge) are shown: ordering food and taking part in activities are not included.
        </p>
    </div>

    <section class="profile-panel profile-panel--table">
        <table class="table profile-table mb-0">
            <thead>
                <tr>
                    <th>Month</th>
                    <th class="text-right">Costs</th>
                    <th class="text-right"><span class="sr-only">Transactions</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($perMonth as $month)
                    @php
                        $monthUrl = action([\Francken\Association\Members\Http\ExpensesController::class, 'show'], [$month['time']->format('Y'), $month['time']->format('m')]);
                    @endphp
                    <tr>
                        <td>
                            <a href="{{ $monthUrl }}" class="profile-table__month">
                                {{ $month['time']->format('F Y') }}
                            </a>
                        </td>
                        <td class="text-right profile-table__price">
                            €{{ number_format($month['price'], 2) }}
                        </td>
                        <td class="text-right">
                            <a href="{{ $monthUrl }}" class="home-link">
                                show transactions <i class="fas fa-arrow-right" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">
                            You have not bought anything at Francken yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
@endsection
