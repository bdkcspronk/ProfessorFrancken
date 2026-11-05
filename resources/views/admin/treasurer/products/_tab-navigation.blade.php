<li class="nav-item">
    <a
        class="{{ $class ?? '' }} font-weight-light nav-link py-3 px-4 {{ $request->category($select) ? 'border-left border-right bg-white' : '' }}"
        {{-- Another category keeps the search, so the counts of the tabs are the products that match it --}}
        href="{{ action([\Francken\Treasurer\Http\Controllers\AdminProductsController::class, 'index'], array_filter([
            'category' => $select,
            'name' => $request->name(),
            'unavailable' => $request->unavailable() ? 1 : null,
        ])) }}"
    >
        {!! $slot !!}
    </a>
</li>
