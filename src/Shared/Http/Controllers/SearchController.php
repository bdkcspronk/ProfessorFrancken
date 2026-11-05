<?php

declare(strict_types=1);

namespace Francken\Shared\Http\Controllers;

use Francken\Association\Activities\Activity;
use Francken\Association\Activities\Http\ActivitiesController;
use Francken\Association\Committees\Committee;
use Francken\Association\Committees\Http\CommitteesController;
use Francken\Association\News\Http\NewsController;
use Francken\Association\News\News;
use Francken\Extern\Http\CompaniesController;
use Francken\Extern\Partner;
use Francken\Extern\SponsorOptions\Vacancy;
use Francken\Shared\Clock\Clock;
use Francken\Shared\Page;
use Francken\Study\BooksSale\Book;
use Francken\Study\BooksSale\Http\BooksController;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\View\View;

/**
 * Searches the pages of the website and its activities, books, committees, news,
 * companies and job openings, and shows the results grouped by their kind
 */
final class SearchController
{
    private const MAX_RESULTS_PER_GROUP = 8;

    private const MAX_WORDS = 5;

    private const SNIPPET_LENGTH = 180;

    /**
     * The pages whose text is written in their view, by their url
     */
    private const PAGES = [
        '/about' => 'pages.about',
        '/association' => 'pages.association',
        '/association/history' => 'pages.association.history',
        '/association/honorary-members' => 'pages.association.honorary-members',
        '/association/francken-vrij' => 'association.francken-vrij',
        '/association/photos' => 'association.photos.index',
        '/study' => 'pages.study',
        '/study/books' => 'book.index',
        '/study/internationals' => 'pages.study.internationals',
        '/career' => 'career.index',
        '/register' => 'registration.index',
        '/contact' => 'pages.contact',
        '/code-of-conduct' => 'pages.code-of-conduct',
        '/privacy-policy' => 'pages.privacy-policy',
        '/cookies-policy' => 'pages.cookies-policy',
    ];

    public function index(Request $request, Clock $clock) : View
    {
        $query = $request->string('q')->trim()->toString();
        $words = $this->words($query);

        $groups = [];
        if (count($words) > 0) {
            $groups = array_values(array_filter([
                $this->group('Pages', 'far fa-file-alt', $words, $this->pages($words)),
                $this->group('Activities', 'fas fa-calendar-week', $words, $this->activities($words)),
                $this->group('Committees', 'fas fa-users', $words, $this->committees($words, $clock)),
                $this->group('Second hand books', 'fas fa-book', $words, $this->books($words)),
                $this->group('News', 'far fa-newspaper', $words, $this->news($words, $clock)),
                $this->group('Companies', 'fas fa-building', $words, $this->companies($words)),
                $this->group('Job openings', 'fas fa-briefcase', $words, $this->jobOpenings($words)),
            ], fn (array $group) : bool => count($group['results']) > 0));
        }

        return view('search.index', [
            'query' => $query,
            'words' => $words,
            'groups' => $groups,
            'total' => array_sum(array_map(fn (array $group) : int => count($group['results']), $groups)),
        ]);
    }

    /**
     * The words to search for: every result has to contain all of them
     *
     * @return string[]
     */
    private function words(string $query) : array
    {
        $words = preg_split('/\s+/u', mb_strtolower($query), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $words = array_filter($words, fn (string $word) : bool => mb_strlen($word) >= 2);

        return array_slice(array_values(array_unique($words)), 0, self::MAX_WORDS);
    }

    /**
     * @param string[] $words
     * @param Collection<int, array{title: string, url: string, text: string, meta?: string}> $results
     */
    private function group(string $title, string $icon, array $words, Collection $results) : array
    {
        return [
            'title' => $title,
            'icon' => $icon,
            'results' => $results->take(self::MAX_RESULTS_PER_GROUP)->map(fn (array $result) : array => [
                'title' => $this->highlight($result['title'], $words),
                'url' => $result['url'],
                'meta' => $result['meta'] ?? null,
                'snippet' => $this->highlight($this->snippet($result['text'], $words), $words),
            ])->all(),
        ];
    }

    /**
     * The pages written in views and the pages made in the admin, with the pages
     * that mention the words most often first
     */
    private function pages(array $words) : Collection
    {
        $pages = collect(self::PAGES)
            ->map(function (string $view, string $url) : ?array {
                $path = resource_path('views/' . str_replace('.', '/', $view) . '.blade.php');

                return file_exists($path)
                    ? ['url' => $url] + $this->textOfView((string) file_get_contents($path))
                    : null;
            })
            ->filter()
            ->values();

        $customPages = $this->whereWords(Page::public(), ['title', 'description', 'source_content'], $words)
            ->get()
            ->toBase()
            ->map(fn (Page $page) : array => [
                'title' => $page->title,
                'url' => '/' . $page->slug,
                'text' => $this->plainText((string) $page->compiled_content),
            ]);

        /** @var Collection<int, array{url: string, title: string, text: string}> $results */
        $results = $pages->concat($customPages);

        return $results
            ->map(fn (array $page) : array => $page + ['score' => $this->score($page, $words)])
            ->filter(fn (array $page) : bool => $page['score'] > 0)
            ->sortByDesc('score')
            ->values();
    }

    private function activities(array $words) : Collection
    {
        return $this->whereWords(Activity::query(), ['name', 'summary', 'source_content', 'location'], $words)
            ->orderByDesc('start_date')
            ->limit(self::MAX_RESULTS_PER_GROUP)
            ->get()
            ->toBase()
            ->map(fn (Activity $activity) : array => [
                'title' => $activity->name,
                'url' => action([ActivitiesController::class, 'show'], ['activity' => $activity]),
                // The summary is the start of the description
                'text' => $this->plainText((string) ($activity->compiled_content ?: $activity->summary)),
                'meta' => $activity->start_date->format('j F Y') . (($activity->location ?? '') !== '' ? ' · ' . $activity->location : ''),
            ]);
    }

    /**
     * Committees exist once for every board, so only the committee of the latest board
     * is shown
     */
    private function committees(array $words, Clock $clock) : Collection
    {
        /** @var \Illuminate\Database\Eloquent\Collection<int, Committee> $committees */
        $committees = $this->whereWords(Committee::query(), ['name', 'goal', 'source_content'], $words)
            ->where('is_public', true)
            ->whereHas('board', fn (Builder $query) => $query->where('installed_at', '<', $clock->now()))
            ->with('board')
            ->get();

        return $committees
            ->toBase()
            ->sortByDesc(fn (Committee $committee) => $committee->board->installed_at)
            ->unique('slug')
            ->values()
            ->map(fn (Committee $committee) : array => [
                'title' => $committee->name,
                'url' => action([CommitteesController::class, 'show'], ['board' => $committee->board, 'committee' => $committee]),
                'text' => $this->plainText($committee->goal . ' ' . $committee->compiled_content),
            ]);
    }

    private function books(array $words) : Collection
    {
        return $this->whereWords(Book::query()->available(), ['title', 'author', 'isbn'], $words)
            ->orderBy('title')
            ->limit(self::MAX_RESULTS_PER_GROUP)
            ->get()
            ->toBase()
            ->map(fn (Book $book) : array => [
                'title' => $book->title,
                'url' => action([BooksController::class, 'show'], ['book' => $book]),
                'text' => $book->author . ' · ISBN ' . $book->isbn,
                'meta' => '€' . number_format($book->price, 2, ',', ''),
            ]);
    }

    private function news(array $words, Clock $clock) : Collection
    {
        return $this->whereWords(News::query(), ['title', 'exerpt', 'source_contents'], $words)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', $clock->now())
            ->recent()
            ->limit(self::MAX_RESULTS_PER_GROUP)
            ->get()
            ->toBase()
            ->map(fn (News $news) : array => [
                'title' => $news->title,
                'url' => action([NewsController::class, 'show'], ['news' => $news]),
                'text' => $this->plainText($news->exerpt . ' ' . $news->compiled_contents),
                'meta' => $news->published_at?->format('j F Y'),
            ]);
    }

    private function companies(array $words) : Collection
    {
        /** @var \Illuminate\Database\Eloquent\Collection<int, Partner> $partners */
        $partners = Partner::query()
            ->whereHas('companyProfile', fn (Builder $query) => $query->where('is_enabled', true))
            ->where(function (Builder $query) use ($words) : void {
                $this->whereWords($query, ['name'], $words)
                    ->orWhereHas('companyProfile', fn (Builder $profile) => $this->whereWords($profile, ['display_name', 'source_content'], $words));
            })
            ->with('companyProfile')
            ->orderBy('name')
            ->limit(self::MAX_RESULTS_PER_GROUP)
            ->get();

        return $partners
            ->toBase()
            ->map(fn (Partner $partner) : array => [
                'title' => (string) $partner->companyProfile?->display_name,
                'url' => action([CompaniesController::class, 'show'], ['partner' => $partner]),
                'text' => $this->plainText((string) $partner->companyProfile?->compiled_content),
            ]);
    }

    private function jobOpenings(array $words) : Collection
    {
        /** @var \Illuminate\Database\Eloquent\Collection<int, Vacancy> $vacancies */
        $vacancies = $this->whereWords(Vacancy::query(), ['title', 'description'], $words)
            ->with('partner')
            ->orderBy('title')
            ->limit(self::MAX_RESULTS_PER_GROUP)
            ->get();

        return $vacancies
            ->toBase()
            ->map(fn (Vacancy $vacancy) : array => [
                'title' => $vacancy->title,
                'url' => $vacancy->vacancy_url ?: '/career/job-openings',
                'text' => $this->plainText((string) $vacancy->description),
                'meta' => $vacancy->partner->name ?? null,
            ]);
    }

    /**
     * Every word has to be in one of the columns
     *
     * @template TModel of \Illuminate\Database\Eloquent\Model
     * @param Builder<TModel> $query
     * @param string[] $columns
     * @param string[] $words
     * @return Builder<TModel>
     */
    private function whereWords(Builder $query, array $columns, array $words) : Builder
    {
        foreach ($words as $word) {
            $like = '%' . str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $word) . '%';

            $query->where(function (Builder $query) use ($columns, $like) : void {
                foreach ($columns as $column) {
                    $query->orWhere($column, 'like', $like);
                }
            });
        }

        return $query;
    }

    /**
     * Pages that contain every word, where words in the title count more
     *
     * @param array{title: string, text: string, ...} $page
     * @param string[] $words
     */
    private function score(array $page, array $words) : int
    {
        $title = mb_strtolower($page['title']);
        $text = mb_strtolower($page['text']);
        $score = 0;

        foreach ($words as $word) {
            $count = 10 * mb_substr_count($title, $word) + mb_substr_count($text, $word);

            if ($count === 0) {
                return 0;
            }

            $score += $count;
        }

        return $score;
    }

    /**
     * The title and the text of a page from the source of its view, without its Blade
     * directives, PHP and HTML
     *
     * @return array{title: string, text: string}
     */
    private function textOfView(string $source) : array
    {
        $title = '';
        if (preg_match("/@section\('page-title'\)(.*?)@endsection/s", $source, $matches) === 1) {
            $title = $this->plainText($this->withoutBlade($matches[1]));
        } elseif (preg_match("/@section\('title',\s*\"(.*?)\s*-\s*T\.F\.V\./s", $source, $matches) === 1) {
            $title = $this->plainText($matches[1]);
        }

        $source = (string) preg_replace("/@section\('page-title'\).*?@endsection/s", '', $source);
        $source = (string) preg_replace("/^\s*@section\('[\w-]+',.*$/m", '', $source);

        return [
            'title' => $title,
            'text' => $this->plainText($this->withoutBlade($source)),
        ];
    }

    private function withoutBlade(string $source) : string
    {
        return (string) preg_replace([
            '/\{\{--.*?--\}\}/s',
            '/@php.*?@endphp/s',
            '/<(script|style)\b.*?<\/\1>/s',
            '/\{!!.*?!!\}|\{\{.*?\}\}/s',
            // Directives with their arguments, but not the @ of an email address
            '/(?<![\w.])@\w+(\s*\((?:[^()]|\((?:[^()]|\([^()]*\))*\))*\))?/',
        ], ' ', $source);
    }

    private function plainText(string $html) : string
    {
        $text = html_entity_decode(strip_tags(str_replace('<', ' <', $html)), ENT_QUOTES | ENT_HTML5);

        return trim((string) preg_replace('/\s+/u', ' ', $text));
    }

    /**
     * A part of the text around the first word that is found in it
     *
     * @param string[] $words
     */
    private function snippet(string $text, array $words) : string
    {
        $lowerText = mb_strtolower($text);
        $positions = array_filter(array_map(fn (string $word) => mb_strpos($lowerText, $word), $words), fn ($position) : bool => $position !== false);
        $position = count($positions) > 0 ? min($positions) : 0;

        $start = max(0, $position - intdiv(self::SNIPPET_LENGTH, 3));
        if ($start > 0) {
            // Start at the beginning of a word
            $space = mb_strpos($text, ' ', $start);
            $start = $space !== false && $space < $position ? $space + 1 : $start;
        }

        $snippet = mb_substr($text, $start, self::SNIPPET_LENGTH);
        if ($start + self::SNIPPET_LENGTH < mb_strlen($text)) {
            $lastSpace = mb_strrpos($snippet, ' ');
            $snippet = ($lastSpace !== false ? mb_substr($snippet, 0, $lastSpace) : $snippet) . ' …';
        }

        return ($start > 0 ? '… ' : '') . $snippet;
    }

    /**
     * The escaped text with the words in a mark element
     *
     * @param string[] $words
     */
    private function highlight(string $text, array $words) : HtmlString
    {
        $pattern = implode('|', array_map(fn (string $word) : string => preg_quote($word, '/'), $words));
        $parts = preg_split('/(' . $pattern . ')/iu', $text, -1, PREG_SPLIT_DELIM_CAPTURE) ?: [$text];

        // The parts at odd positions are the words that were found
        $html = '';
        foreach ($parts as $index => $part) {
            $html .= $index % 2 === 1 ? '<mark>' . e($part) . '</mark>' : e($part);
        }

        return new HtmlString($html);
    }
}
