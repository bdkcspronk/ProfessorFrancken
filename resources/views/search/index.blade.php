@extends('layout.one-column-layout')

@section('page-title')
    Search
@endsection
@section('title', ($query !== '' ? "Search for “" . $query . "”" : 'Search') . " - T.F.V. ‘Professor Francken’")
@section('description', "Search the pages, activities, committees, books and news of T.F.V. ‘Professor Francken’.")

@section('content')
    <form class="search-form" action="{{ route('search') }}" method="get" role="search">
        <label class="sr-only" for="search-query">Search the website</label>
        <input
            id="search-query"
            class="search-form__input"
            type="search"
            name="q"
            value="{{ $query }}"
            placeholder="Activities, committees, books, pages…"
            @if ($query === '') autofocus @endif
        >
        <button class="btn home-btn home-btn--crimson" type="submit">
            <i class="fas fa-search" aria-hidden="true"></i>
            Search
        </button>
    </form>

    @if ($query !== '')
        <p class="search-summary">
            @if (count($words) === 0)
                Type at least two letters to search.
            @elseif ($total === 0)
                Nothing was found for “{{ $query }}”. Try other or fewer words.
            @else
                {{ $total }} {{ \Illuminate\Support\Str::plural('result', $total) }} for “{{ $query }}”
            @endif
        </p>

        @foreach ($groups as $group)
            <section class="search-group">
                <h2 class="search-group__title">
                    <i class="{{ $group['icon'] }} fa-fw" aria-hidden="true"></i>
                    {{ $group['title'] }}
                    <span class="search-group__count">{{ count($group['results']) }}</span>
                </h2>

                @foreach ($group['results'] as $result)
                    <a class="search-result" href="{{ $result['url'] }}">
                        <span class="search-result__title">{{ $result['title'] }}</span>
                        @if ($result['meta'] !== null)
                            <span class="search-result__meta">{{ $result['meta'] }}</span>
                        @endif
                        @if ((string) $result['snippet'] !== '')
                            <span class="search-result__snippet">{{ $result['snippet'] }}</span>
                        @endif
                    </a>
                @endforeach
            </section>
        @endforeach
    @endif
@endsection
