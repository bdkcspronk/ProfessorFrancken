@extends('admin.layout')
@section('page-title', 'News')
@php
use Francken\Association\News\Http\AdminNewsController;
@endphp

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <p class="text-muted small mb-0">
                        News, blogs and announcements of the association. Click a news item to edit it.
                    </p>

                    @if (count($drafts) > 0)
                        <h3 class="h5 mt-4 mb-0">
                            Drafts <small class="text-muted">{{ count($drafts) }}</small>
                        </h3>
                    @endif
                </div>

                @include('admin.news._table', ['news' => $drafts])

                <div class="card-body">
                    <h3 class="h5 mb-0">
                        Published news
                    </h3>
                </div>

                @include('admin.news._table', ['news' => $news])

                @if ($news->isEmpty())
                    <p class="text-center text-muted py-4 mb-0">No news found.</p>
                @endif

                @if ($news->hasPages())
                    <div class="card-footer">
                        {!! $news->links() !!}
                    </div>
                @endif
            </div>


        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title section-header agenda-header">
                        <label for="search-news">
                            Search for news
                        </label>
                    </h3>
                    <ul class="agenda-list list-unstyled">
                        <li class="agenda-item" style="margin-bottom: .5em; padding-bottom: .5em;">

                            <form action="{{ action([AdminNewsController::class, 'index']) }}" method="GET" class="form-horizontal">
                                <x-forms.text name="subject" label="Subject" placeholder="Search by subject" />
                                <x-forms.text name="author" label="Author" placeholder="Search by author" />

                                <div class="row">
                                    <div class="col">
                                        <x-forms.date name="published_before">
                                            <x-slot name="label">
                                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                                Published before
                                            </x-slot>
                                        </x-forms.date>
                                    </div>
                                    <div class="col">
                                        <x-forms.date name="published_after">
                                            <x-slot name="label">
                                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                                Published after
                                            </x-slot>
                                        </x-forms.date>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-block btn-primary">Apply filters</button>
                            </form>
                        </li>
                    </ul>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('actions')
    <div class="d-flex align-items-end">
        <a
            class="btn btn-primary"
            href="{{ action([AdminNewsController::class, 'create']) }}"
        >
            <i class="fa fa-plus mr-1" aria-hidden="true"></i>
            Write a new news post
        </a>
    </div>
@endsection
