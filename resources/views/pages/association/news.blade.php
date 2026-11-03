@extends('pages.association')

@section('page-title')
    News
@endsection

@section('title', "Recent news - T.F.V. ‘Professor Francken’")

@section('main-content')
    <div class="container my-5">
        <div class="row">
            @foreach ($news as $newsItem)
                {{-- The newest item is shown larger --}}
                <div class="{{ $loop->first && $news->onFirstPage() ? 'col-12' : 'col-md-6 col-lg-4' }} mb-4">
                    <a @class(['news-card', 'news-card--featured' => $loop->first && $news->onFirstPage()]) href="{{ action([\Francken\Association\News\Http\NewsController::class, 'show'], ['news' => $newsItem]) }}">
                        <span class="news-card__date">{{ $newsItem->published_at->format('j F Y') }}</span>
                        <h2 class="news-card__title">{{ $newsItem->title }}</h2>
                        <span class="news-card__author">By {{ $newsItem->author_name }}</span>
                        <p class="news-card__excerpt">{!! strip_tags($newsItem->exerpt, '<em><strong><b><i>') !!}</p>
                        <span class="home-link">Read more <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
                    </a>
                </div>
            @endforeach
        </div>

        {!! $news->links() !!}

        <p class="text-center mt-4">
            <a href="{{ action([\Francken\Association\News\Http\NewsController::class, 'archive']) }}" class="home-link">
                Search the news archive <i class="fas fa-arrow-right" aria-hidden="true"></i>
            </a>
        </p>
    </div>
@endsection
