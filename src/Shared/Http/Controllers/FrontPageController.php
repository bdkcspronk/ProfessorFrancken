<?php

declare(strict_types=1);

namespace Francken\Shared\Http\Controllers;

use DateTimeImmutable;
use DateTimeZone;
use Francken\Association\Activities\Activity;
use Francken\Association\FranckenVrij\Edition;
use Francken\Association\News\News;
use Francken\Shared\Page;
use Francken\Shared\Settings\Settings;
use Illuminate\View\View;

class FrontPageController extends Controller
{
    public function index(Settings $settings) : View
    {
        $today = new DateTimeImmutable(
            'now',
            new DateTimeZone('Europe/Amsterdam')
        );

        $latestEdition = Edition::query()
            ->latestEdition()
            ->first();

        $news = News::query()->recent()->limit(3)->get();

        $activities = Activity::query()
             ->with(['signUpSettings', 'signUps'])
             ->withCount(['comments'])
            ->after($today)
            ->orderBy('start_date', 'asc')
            ->limit(8)
            ->get();

        // Group the activities by the week in which they start, activities
        // that started before this week but are still going on count as this week
        $startOfNextWeek = $today->modify('monday next week')->setTime(0, 0);
        $startOfWeekAfterNext = $startOfNextWeek->modify('+1 week');

        $agenda = $activities->groupBy(function (Activity $activity) use ($startOfNextWeek, $startOfWeekAfterNext) : string {
            if ($activity->start_date < $startOfNextWeek) {
                return 'This week';
            }

            if ($activity->start_date < $startOfWeekAfterNext) {
                return 'Next week';
            }

            return 'Upcoming';
        });

        $covid = Page::covid()->where('is_published', true)->first();

        return view('homepage/homepage', [
            'header_image' => $settings->headerImage(),
            'news' => $news,
            'agenda' => $agenda,
            'latest_edition' => $latestEdition,
            'covid' => $covid,
        ]);
    }
}
