<?php

declare(strict_types=1);

namespace Francken\Features;

use DateTimeImmutable;
use DateTimeZone;
use Francken\Association\Activities\Activity;
use Francken\Association\FranckenVrij\Edition;
use Francken\Association\FranckenVrij\EditionId;
use Francken\Shared\Url;

class FrontPageFeature extends TestCase
{
    /**
     * Checks if we can open the front page
     *
     * @test
     */
    public function the_front_page_shows_our_name() : void
    {
        $this->publishAFranckenVrij();

        $this->visit('/')
             ->see("T.F.V. ‘Professor Francken’");
    }

    /** @test */
    public function the_agenda_is_grouped_by_week() : void
    {
        $now = new DateTimeImmutable('now', new DateTimeZone('Europe/Amsterdam'));
        $startOfNextWeek = $now->modify('monday next week')->setTime(0, 0);

        $this->createActivity('Borrel today', $now, $now->modify('+2 hours'));
        $this->createActivity('Lab tour next week', $startOfNextWeek->modify('+1 day 12:00'));
        $this->createActivity('Symposium later on', $startOfNextWeek->modify('+3 weeks'));

        $this->visit('/')
            ->seeInElement('.home-agenda', 'This week')
            ->seeInElement('.home-agenda', 'Next week')
            ->seeInElement('.home-agenda', 'Upcoming');

        $this->assertTextOrder([
            'This week', 'Borrel today',
            'Next week', 'Lab tour next week',
            'Upcoming', 'Symposium later on',
        ]);
    }

    /** @test */
    public function the_agenda_only_shows_weeks_with_activities() : void
    {
        $startOfNextWeek = (new DateTimeImmutable('now', new DateTimeZone('Europe/Amsterdam')))
            ->modify('monday next week')
            ->setTime(0, 0);

        $this->createActivity('Lab tour next week', $startOfNextWeek->modify('+1 day 12:00'));

        $this->visit('/')
            ->seeInElement('.home-agenda', 'Next week')
            ->dontSeeInElement('.home-agenda', 'This week')
            ->dontSeeInElement('.home-agenda', 'Upcoming');
    }

    private function createActivity(string $name, DateTimeImmutable $start, ?DateTimeImmutable $end = null) : void
    {
        factory(Activity::class)->create([
            'name' => $name,
            'start_date' => $start,
            'end_date' => $end ?? $start->modify('+2 hours'),
        ]);
    }

    private function assertTextOrder(array $texts) : void
    {
        $content = $this->crawler->filter('.home-agenda')->text();
        $positions = array_map(fn (string $text) => mb_strpos($content, $text), $texts);

        $this->assertNotContains(false, $positions, 'Not all texts are shown on the page');
        $this->assertSame($positions, collect($positions)->sort()->values()->all());
    }

    private function publishAFranckenVrij() : void
    {
        Edition::publish(
            EditionId::generate(),
            "Francken Vrij 20.1",
            20,
            1,
            new Url("http://www.professorfrancken.nl/franckenvrij/webplaatjes/20.1.jpg"),
            new Url("http://www.professorfrancken.nl/franckenvrij/20.1.pdf")
        );
    }
}
