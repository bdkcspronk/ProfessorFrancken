<?php

declare(strict_types=1);

namespace Francken\Features\Study\Books;

use Francken\Features\TestCase;
use Francken\Study\BooksSale\Book;

class BooksSearchFeature extends TestCase
{
    /** @test */
    public function every_word_has_to_be_in_the_title_author_or_isbn() : void
    {
        $this->bookForSale('Classical Mechanics', 'John R. Taylor', '9781891389221');
        $this->bookForSale('Quantum Mechanics', 'David J. Griffiths', '9781107189638');

        $this->visit('/study/books?search=mechanics+taylor')
            ->see('1 book found for')
            ->see('Classical Mechanics')
            ->dontSee('Quantum Mechanics');

        $this->visit('/study/books?search=978-1107189638')
            ->see('Quantum Mechanics')
            ->dontSee('Classical Mechanics');
    }

    /** @test */
    public function it_says_when_no_books_were_found() : void
    {
        $this->bookForSale('Classical Mechanics', 'John R. Taylor', '9781891389221');

        $this->visit('/study/books?search=thermodynamics')
            ->see('No books were found for')
            ->click('Show all books')
            ->see('Classical Mechanics');
    }

    private function bookForSale(string $title, string $author, string $isbn) : void
    {
        factory(Book::class)->create([
            'title' => $title,
            'author' => $author,
            'isbn' => $isbn,
            'buyer_id' => null,
            'taken_in_by_buyer_at' => null,
            'has_been_sold' => false,
            'paid_off' => false,
        ]);
    }
}
