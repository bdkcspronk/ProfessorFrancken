<?php

declare(strict_types=1);

namespace Francken\Features;

use DateTimeImmutable;
use Francken\Association\Activities\Activity;
use Francken\Study\BooksSale\Book;

class SearchFeature extends TestCase
{
    /** @test */
    public function it_finds_pages_activities_and_books() : void
    {
        factory(Activity::class)->create([
            'name' => 'Quantum borrel',
            'start_date' => new DateTimeImmutable('2026-10-01 16:00'),
            'end_date' => new DateTimeImmutable('2026-10-01 20:00'),
        ]);
        $this->bookForSale('Quantum Mechanics', 'David J. Griffiths');

        $this->visit('/search?q=quantum')
            ->seeInElement('.search-group', 'Activities')
            ->seeInElement('.search-result__title', 'borrel')
            ->seeInElement('.search-result__title', 'Mechanics');

        $this->visit('/search?q=honorary')
            ->seeElement('a.search-result[href="/association/honorary-members"]');
    }

    /** @test */
    public function every_word_has_to_be_found() : void
    {
        $this->bookForSale('Classical Mechanics', 'John R. Taylor');
        $this->bookForSale('Quantum Mechanics', 'David J. Griffiths');

        $this->visit('/search?q=mechanics+taylor')
            ->see('1 result for')
            ->see('Classical')
            ->dontSee('Griffiths');
    }

    /** @test */
    public function it_asks_for_at_least_two_letters() : void
    {
        $this->visit('/search?q=a')
            ->see('Type at least two letters to search.');
    }

    /** @test */
    public function it_says_when_nothing_was_found() : void
    {
        $this->visit('/search?q=xyzzy')
            ->see('Nothing was found for');
    }

    private function bookForSale(string $title, string $author) : void
    {
        factory(Book::class)->create([
            'title' => $title,
            'author' => $author,
            'buyer_id' => null,
            'taken_in_by_buyer_at' => null,
            'has_been_sold' => false,
            'paid_off' => false,
        ]);
    }
}
