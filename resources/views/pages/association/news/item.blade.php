@extends('layout.two-column-layout')

@section('page-title')
    {{ $newsItem->title }}
@endsection

@section('title', $newsItem->title . " - T.F.V. ‘Professor Francken’")
@section('description', $newsItem->exerpt)

@section('content')
    <article class="news-article">
        <div class="news-article__meta">
            {{ $newsItem->published_at->format('j F Y') }} · By {{ $newsItem->author_name }}
        </div>
        <div class="cms-page news-article__content">
            {!! $newsItem->compiled_contents !!}
        </div>
    </article>

    {{-- The older and newer news items --}}
    <nav class="news-pager" aria-label="Other news">
        @unless ($previous === null)
            <a class="news-pager__link" href="{{ action([\Francken\Association\News\Http\NewsController::class, 'show'], ['news' => $previous]) }}">
                <span class="news-pager__label"><i class="fas fa-arrow-left" aria-hidden="true"></i> Previous news</span>
                {{ $previous->title }}
            </a>
        @endunless

        @unless ($next === null)
            <a class="news-pager__link news-pager__link--next" href="{{ action([\Francken\Association\News\Http\NewsController::class, 'show'], ['news' => $next]) }}">
                <span class="news-pager__label">Next news <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
                {{ $next->title }}
            </a>
        @endunless
    </nav>
@endsection

@section('aside')
    <a class="home-link d-inline-block mb-3" href="{{ action([\Francken\Association\News\Http\NewsController::class, 'index']) }}">
        <i class="fas fa-arrow-left" aria-hidden="true"></i>
        All news
    </a>

    <div class="news-author">
        <h2 class="news-author__heading">Written by</h2>
        <div class="news-author__person">
            @if (($newsItem->author_photo ?? '') !== '')
                <img class="news-author__photo" src="{{ image($newsItem->author_photo, ['height' => 150, 'width' => 150]) }}" alt="">
            @else
                <span class="news-author__photo news-author__photo--empty" aria-hidden="true"><i class="fas fa-user"></i></span>
            @endif
            <span class="news-author__name">{{ $newsItem->author_name }}</span>
        </div>
    </div>
@endsection
