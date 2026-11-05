@extends('admin.layout')
@section('page-title', 'Products')

@section('content')
    <div class="card">
        <div class="card-header p-0">
            <ul class="nav nav-tabs card-header-tabs m-0">
                @component('admin.treasurer.products._tab-navigation', ['request' => $request, 'select' => 'all', 'class' => 'border-left-0'])
                    <i class="fas fa-store"></i>
                    All products
                    <span class="mx-1 badge badge-secondary text-white">
                        {{ $totalProducts }}
                    </span>
                @endcomponent
                @component('admin.treasurer.products._tab-navigation', ['request' => $request, 'select' => 'beer'])
                    <i class="fas fa-beer"></i>
                    Beer
                    <span class="mx-1 badge badge-secondary text-white">
                        {{ $beerProducts }}
                    </span>
                @endcomponent
                @component('admin.treasurer.products._tab-navigation', ['request' => $request, 'select' => 'food'])
                    <i class="fas fa-drumstick-bite"></i>
                    Food
                    <span class="mx-1 badge badge-secondary text-white">
                        {{ $foodProducts }}
                    </span>
                @endcomponent
                @component('admin.treasurer.products._tab-navigation', ['request' => $request, 'select' => 'soda'])
                    <i class="fas fa-coffee"></i>
                    Soda
                    <span class="mx-1 badge badge-secondary text-white">
                        {{ $sodaProducts }}
                    </span>
                @endcomponent
            </ul>
        </div>

        <div class="card-body">
            <h4 class="font-weight-bold">
                Search
            </h4>
            <form action="{{ action([\Francken\Treasurer\Http\Controllers\AdminProductsController::class, 'index']) }}"
                  method="GET"
                  class="form"
            >
                {{-- Searching stays in the category that is open --}}
                <input type="hidden" name="category" value="{{ $request->input('category', 'all') }}">
                <div class="d-flex mb-3">
                    <div class="form-group mr-2 mb-0">
                        <label for="name">Name</label>
                        {!! Form::text('name', $request->name(), ['placeholder' => 'Search by name', 'class' => 'form-control'])  !!}
                    </div>
                    <div class="d-flex justify-content-between align-items-end">
                        <button type="submit" class="mx-2 btn btn-sm btn-primary">
                            <i class="fas fa-search"></i>
                            Apply filters
                        </button>
                        <a href="{{ action([\Francken\Treasurer\Http\Controllers\AdminProductsController::class, 'index'], ['category' => $request->input('category', 'all')])  }}"
                           class="btn btn-sm btn-text text-primary"
                        >
                            <i class="fas fa-times"></i>
                            Clear filters
                        </a>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <div class="form-group form-check mx-2">
                        {!! Form::checkbox('unavailable', true, $request->unavailable(), ['class' => 'form-check-input', 'id' => 'unavailable'])  !!}
                        <label class="form-check-label" for="unavailable">Also show unavailable products</label>
                    </div>
                </div>
            </form>
        </div>

        <table class="table table-hover table-sm mb-0 treasurer-table">
            <thead>
                <tr>
                    <th colspan="2">Product</th>
                    <th>Category</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">VAT</th>
                    <th class="text-right">Available</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    @php
                        $url = action([\Francken\Treasurer\Http\Controllers\AdminProductsController::class, 'show'], ['product' => $product]);
                        // The VAT is a fraction, like 0.21 for 21%
                        $vat = $product->btw <= 1 ? $product->btw * 100 : $product->btw;
                    @endphp
                    <tr>
                        <td class="treasurer-table__photo">
                            <a href="{{ $url }}" tabindex="-1" aria-hidden="true">
                                {{-- The photos are on the old website; without a photo the icon of its category is shown --}}
                                <span class="treasurer-product-photo">
                                    <i class="fas fa-{{ $product->category_icon }}"></i>
                                    <img src="{{ $product->photo_url }}" alt="" loading="lazy" onerror="this.remove()">
                                </span>
                            </a>
                        </td>
                        <td>
                            <a href="{{ $url }}" class="font-weight-bold">{{ $product->name }}</a>
                        </td>
                        <td class="text-muted">{{ $product->category }}</td>
                        <td class="text-right text-nowrap">&euro;{{ number_format($product->price / 100, 2) }}</td>
                        <td class="text-right text-nowrap">{{ rtrim(rtrim(number_format($vat, 2), '0'), '.') }}%</td>
                        <td class="text-right">
                            @if ($product->available)
                                <span class="badge badge-success">Available</span>
                            @else
                                <span class="badge badge-secondary">Not available</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="card-footer">
            {!! $products->links() !!}
        </div>
    </div>
@endsection

@section('actions')
    <div class="d-flex align-items-end">
        <a href="{{ action([\Francken\Treasurer\Http\Controllers\AdminProductsController::class, 'create']) }}"
           class="btn btn-primary"
        >
            <i class="fas fa-plus"></i>
            Add a product
        </a>
    </div>
@endsection
