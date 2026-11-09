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
    public function the_agenda_only_shows_upcoming_activities_when_there_are_any() : void
    {
        $startOfNextWeek = (new DateTimeImmutable('now', new DateTimeZone('Europe/Amsterdam')))
            ->modify('monday next week')
            ->setTime(0, 0);

        $this->createActivity('Upcoming activity', $startOfNextWeek->modify('+1 day 12:00'));
        $this->createActivity('Later activity', $startOfNextWeek->modify('+2 weeks'));

        $this->visit('/')
            ->seeInElement('.home-agenda', 'Upcoming activities')
            ->seeInElement('.home-agenda', 'Upcoming activity')
            ->dontSeeInElement('.home-agenda', 'Later');
    }

    /** @test */
    public function the_agenda_shows_later_activities_when_there_are_no_upcoming_ones() : void
    {
        $startOfNextWeek = (new DateTimeImmutable('now', new DateTimeZone('Europe/Amsterdam')))
            ->modify('monday next week')
            ->setTime(0, 0);

        foreach (range(1, 9) as $day) {
            $this->createActivity("Later activity {$day}", $startOfNextWeek->modify("+1 week {$day} days"));
        }

        $this->visit('/')
            ->seeInElement('.home-agenda', 'Later')
            ->dontSeeInElement('.home-agenda', 'Upcoming activities')
            ->dontSeeInElement('.home-agenda', 'Later activity 9');

        $this->assertTextOrder(['Later', 'Later activity 1', 'Later activity 8']);
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
