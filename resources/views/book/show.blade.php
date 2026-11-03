@extends('layout.one-column-layout')

@section('page-title')
    Second hand books
@endsection
@section('header-image-url', '/images/header/library-books.jpeg')
@section('title', "Buy \"" . $book->title . "\" - T.F.V. ‘Professor Francken’")

@section('content')
    <a class="home-link d-inline-block mb-4" href="{{ action([\Francken\Study\BooksSale\Http\BooksController::class, 'index']) }}">
        <i class="fas fa-arrow-left" aria-hidden="true"></i>
        All second hand books
    </a>

    <div class="row">
        <div class="col-sm-5 col-md-4 mb-4">
            <div class="book-card__cover book-detail__cover">
                <img src="{{ $book->cover_path }}" alt="Cover of {{ $book->title }}">
            </div>
        </div>
        <div class="col-sm-7 col-md-8">
            <div class="book-detail">
                <h2 class="book-detail__title">{{ $book->title }}</h2>
                <div class="book-detail__author">{{ $book->author }}</div>

                <div class="book-detail__price">€{{ number_format($book->price, 2, ",", "") }}</div>

                <dl class="book-detail__facts">
                    <dt>ISBN</dt>
                    <dd>{{ $book->isbn }}</dd>
                </dl>

                <div class="book-detail__buy">
                    <i class="fas fa-shopping-bag" aria-hidden="true"></i>
                    <p class="mb-0">
                        Want this book? Come to the Francken room (Feringa Building, room 5614.0143) and ask one of the board members, or mail us at <a class="home-link" href="mailto:books@professorfrancken.nl">books@professorfrancken.nl</a>. You can pay in cash, scan the QR code in the room, or have the amount taken from your bank account with the next direct debit.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
