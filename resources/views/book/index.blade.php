@extends('layout.one-column-layout')

@section('page-title')
    Second hand books
@endsection
@section('title', "Books - T.F.V. ‘Professor Francken’")
@section('description', "Buy or sell second hand study books.")
@section('header-image-url', '/images/header/library-books.jpeg')

@section('content')
    <div class="row mb-5">
        <div class="col-lg-8">
            <div class="page-intro mb-4 mb-lg-0">
                <p>
                    Looking for a study book? Below you’ll find all second hand books that are for sale at Francken. Just drop by the Francken room (Feringa Building, room 5614.0143) to pick up your book. You can pay in cash, scan the QR code in the room, or have the amount taken from your bank account with the next direct debit.
                </p>
                <p>
                    Questions? Mail us at <a class="home-link" href="mailto:books@professorfrancken.nl">books@professorfrancken.nl</a>
                </p>
            </div>
        </div>
        <div class="col-lg-4">
            <aside class="book-sell">
                <h2 class="book-sell__title">
                    <i class="fas fa-hand-holding-usd" aria-hidden="true"></i>
                    Selling your books?
                </h2>
                <p class="mb-0">
                    That’s also possible! Bring your books to the board room and set your own price. All the money goes to you: Francken doesn’t keep any of it.
                </p>
            </aside>
        </div>
    </div>

    <div class="book-search-bar">
        <h2 class="home-heading">For sale</h2>
        <form class="book-search" action="{{ action([\Francken\Study\BooksSale\Http\BooksController::class, 'index']) }}" method="get" role="search">
            <label class="sr-only" for="book-search">Search for a book</label>
            <input id="book-search" class="book-search__input" type="search" name="search" value="{{ $search }}" placeholder="Title, author or ISBN">
            <button class="book-search__button" type="submit" aria-label="Search">
                <i class="fas fa-search" aria-hidden="true"></i>
            </button>
        </form>
    </div>

    @if ($search !== '')
        <p class="book-search__summary">
            @if ($books->total() === 0)
                No books were found for “{{ $search }}”.
            @else
                {{ $books->total() }} {{ \Illuminate\Support\Str::plural('book', $books->total()) }} found for “{{ $search }}”.
            @endif
            <a class="home-link ml-2" href="{{ action([\Francken\Study\BooksSale\Http\BooksController::class, 'index']) }}">Show all books</a>
        </p>
    @endif

    <div class="row">
        @foreach ($books as $book)
            <div class="col-6 col-md-4 col-lg-3 mb-4">
                <a
                    class="book-card"
                    href="{{ action([\Francken\Study\BooksSale\Http\BooksController::class, 'show'], ['book' => $book]) }}"
                >
                    <span class="book-card__cover">
                        {{-- Shown when there is no cover of the book --}}
                        <span class="book-card__placeholder" aria-hidden="true">
                            <i class="fas fa-book"></i>
                            <span>{{ $book->title }}</span>
                        </span>
                        <img src="{{ image($book->cover_path, ['height' => 300, 'width' => 220]) }}" alt="Cover of {{ $book->title }}" onerror="this.remove()">
                        <span class="book-card__price">€{{ number_format($book->price, 2, ",", "") }}</span>
                    </span>
                    <span class="book-card__title">{{ $book->title }}</span>
                    <span class="book-card__author">{{ $book->author }}</span>
                    <span class="book-card__isbn">ISBN {{ $book->isbn }}</span>
                </a>
            </div>
        @endforeach
    </div>

    {!! $books->links() !!}
@endsection
