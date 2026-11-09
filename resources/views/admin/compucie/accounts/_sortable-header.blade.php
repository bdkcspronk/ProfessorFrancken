{{-- A header of the table of accounts that sorts the accounts by its column, or reverses the order when they already are --}}
@php
    $isSorted = $sort === $column;
    $nextDirection = $isSorted && $direction === 'asc' ? 'desc' : 'asc';
@endphp
<th @if ($isSorted) aria-sort="{{ $direction === 'asc' ? 'ascending' : 'descending' }}" @endif>
    <a
        href="{{ action([\Francken\Auth\Http\Controllers\Admin\AccountsController::class, 'index'], array_filter(['search' => $search, 'sort' => $column, 'direction' => $nextDirection])) }}"
        class="text-reset text-nowrap"
    >
        {{ $label }}
        @if ($isSorted)
            <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}" aria-hidden="true"></i>
        @else
            <i class="fas fa-sort text-muted" aria-hidden="true"></i>
        @endif
    </a>
</th>
