<?php

declare(strict_types=1);

namespace Francken\Association\News\Http\Requests;

use DateTimeImmutable;
use Illuminate\Foundation\Http\FormRequest;
use League\Period\Period;
use Webmozart\Assert\Assert;

class SearchNewsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules() : array
    {
        return [
            'subject' => ['nullable', 'min:1', ],
            'author' => ['nullable', 'min:1', ],
            'published_before' => ['nullable', 'date_format:Y-m-d'],
            'published_after' => ['nullable', 'date_format:Y-m-d'],
        ];
    }

    public function subject() : ?string
    {
        return $this->input('subject');
    }

    public function author() : ?string
    {
        return $this->input('author');
    }

    public function publishedBefore() : ?DateTimeImmutable
    {
        if ($this->input('published_before') === null) {
            return null;
        }

        $publishedBefore = DateTimeImmutable::createFromFormat(
            'Y-m-d',
            $this->input('published_before')
        );

        Assert::isInstanceOf($publishedBefore, DateTimeImmutable::class);

        return $publishedBefore;
    }

    public function publishedAfter() : ?DateTimeImmutable
    {
        if ($this->input('published_after') === null) {
            return null;
        }

        $publishedAfter = DateTimeImmutable::createFromFormat(
            'Y-m-d',
            $this->input('published_after')
        );

        Assert::isInstanceOf($publishedAfter, DateTimeImmutable::class);

        return $publishedAfter;
    }

    /**
     * The period in which the news was published, or null to search all published news
     */
    public function period() : ?Period
    {
        $publishedAfter = $this->publishedAfter();
        $publishedBefore = $this->publishedBefore();

        if ($publishedAfter === null && $publishedBefore === null) {
            return null;
        }

        return new Period(
            ($publishedAfter ?? new DateTimeImmutable('1900-01-01'))->setTime(0, 0),
            ($publishedBefore ?? new DateTimeImmutable('+10 years'))->setTime(23, 59, 59)
        );
    }
}
