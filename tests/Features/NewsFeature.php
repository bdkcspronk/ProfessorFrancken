<?php

declare(strict_types=1);

namespace Francken\Features;

use DateTimeImmutable;
use Faker\Factory;
use Francken\Association\News\Fake\FakeNews;
use Francken\Association\News\Http\NewsController;
use Francken\Association\News\News;

class NewsFeature extends TestCase
{
    /** @before */
    public function setupNews() : void
    {
        $this->afterApplicationCreated(function () : void {
            $faker = Factory::create();
            $faker->seed(31415);

            $fakeNews = new FakeNews($faker, 10);
            $news = $fakeNews->all();

            foreach ($news as $newsItem) {
                $newsItem->save();
            }
        });
    }

    /** @test */
    public function the_latest_news_is_shown() : void
    {
        $this->visit(
            action([NewsController::class, 'index'])
        )
            ->click('Read more')

            // Check that we now are on a news item page
            // which contains info about its author
            ->see('Written by');

        $this->assertResponseOk();
    }

    /** @test */
    public function the_archive_can_be_used_to_search_for_old_news() : void
    {
        $this->visit(
            action([NewsController::class, 'archive'])
        );

        $this->assertResponseOk();
    }

    /** @test */
    public function the_archive_shows_old_news_and_searches_on_part_of_the_subject_and_author() : void
    {
        $old = News::create([
            'title' => 'Borel Borel Borel Distribution',
            'slug' => 'borel-borel-borel-distribution',
            'related_news_items' => [],
            'exerpt' => '',
            'author_name' => 'Steven Groen',
            'author_photo' => '',
            'source_contents' => '',
            'compiled_contents' => '',
        ]);
        $old->publish(new DateTimeImmutable('2017-09-29'));
        $old->save();

        $this->visit(action([NewsController::class, 'archive']))
            ->see('Borel Borel Borel Distribution');

        $this->visit(action([NewsController::class, 'archive'], ['subject' => 'borel', 'author' => 'groen']))
            ->see('Borel Borel Borel Distribution');

        $this->visit(action([NewsController::class, 'archive'], ['published_after' => '2018-01-01']))
            ->dontSee('Borel Borel Borel Distribution');
    }
}
