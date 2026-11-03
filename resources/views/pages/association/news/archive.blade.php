@extends('layout.two-column-layout')

@section('page-title')
    News Archive
@endsection

@section('title', "News archive - T.F.V. ‘Professor Francken’")

@section('content')
    @if (count($news) > 0)
        <ul class="news-archive">
            @foreach ($news as $item)
                <li>
                    <a class="news-archive__item" href="{{ action([\Francken\Association\News\Http\NewsController::class, 'show'], ['news' => $item]) }}">
                        <span class="news-archive__title">{{ $item->title }}</span>
                        <span class="news-archive__meta">{{ $item->published_at->format('j F Y') }} · {{ $item->author_name }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-muted">No news items match your search.</p>
    @endif

    {!! $news->links() !!}
@endsection

@section('aside')
    <a class="home-link d-inline-block mb-3" href="{{ action([\Francken\Association\News\Http\NewsController::class, 'index']) }}">
        <i class="fas fa-arrow-left" aria-hidden="true"></i>
        Latest news
    </a>

    <form class="news-search" action="{{ action([\Francken\Association\News\Http\NewsController::class, 'archive']) }}" method="GET">
        <h2 class="news-author__heading">Search the archive</h2>

        <div class="form-group">
            <label for="subject">Subject</label>
            {!! Form::text('subject', null, ['id' => 'subject', 'placeholder' => 'Sustainability', 'class' => 'form-control'])  !!}
        </div>

        <div class="form-group">
            <label for="author">Author</label>
            {!! Form::text('author', null, ['id' => 'author', 'placeholder' => 'Name', 'class' => 'form-control'])  !!}
        </div>

        <div class="form-row">
            <div class="form-group col-6">
                <label for="published_after">Published after</label>
                {!! Form::date('published_after', null, ['id' => 'published_after', 'class' => 'form-control'])  !!}
            </div>
            <div class="form-group col-6">
                <label for="published_before">Published before</label>
                {!! Form::date('published_before', null, ['id' => 'published_before', 'class' => 'form-control'])  !!}
            </div>
        </div>

        <button type="submit" class="btn home-btn home-btn--crimson btn-block">
            <i class="fas fa-search" aria-hidden="true"></i> Search
        </button>
    </form>
@endsection
