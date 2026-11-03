@extends('profile.layout')

@section('content')
    <a href="{{ action([\Francken\Association\Members\Http\ExpensesController::class, 'index']) }}" class="home-link profile-back">
        <i class="fas fa-arrow-left" aria-hidden="true"></i> All months
    </a>
    <h2 class="home-heading profile-heading">Expenses in {{ $date }}</h2>

    @forelse ($byDay as $transactions)
        <section class="profile-panel profile-panel--table">
            <header class="profile-panel__header profile-day">
                <h3 class="profile-panel__title">
                    {{ $transactions->first()['time']->format('l') }}
                    <span class="profile-list__meta">{{ $transactions->first()['time']->format('j F') }}</span>
                </h3>
                <strong class="profile-table__price">
                    €{{ number_format($transactions->sum('price'), 2, '.', '') }}
                </strong>
            </header>
            <table class="table profile-table mb-0">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Product</th>
                        <th class="text-right">Price</th>
                        <th class="text-right">Deducted on</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction['time']->format('H:i') }}</td>
                            <td>{{ $transaction['product'] }}</td>
                            <td class="text-right">€{{ number_format($transaction['price'], 2, '.', '') }}</td>
                            <td class="text-right">{{ $transaction['deducted_at'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    @empty
        <section class="profile-panel profile-panel--empty">
            <i class="fas fa-receipt" aria-hidden="true"></i>
            <p class="mb-0">You did not buy anything in this month.</p>
        </section>
    @endforelse
@endsection
