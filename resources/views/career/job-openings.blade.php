@extends('layout.two-column-layout')

@section('page-title')
    Jobs and internships
@endsection
@section('header-image-url', '/images/header/oslo.jpg')
@section('title', "Job openings and internships - T.F.V. ‘Professor Francken’")
@section('description', "Find job openings and internships related to engineering physics.")

@section('content')
    <div class="page-intro">
        <p>Are you looking for a job or an internship? These companies are looking for you.</p>
    </div>

    @forelse ($vacancies as $vacancy)
        <article class="job-card">
            <div class="job-card__logo">
                @if ($vacancy->partner->logo !== null)
                    <img src="{{ $vacancy->partner->logo }}" alt="{{ $vacancy->partner->name }}">
                @else
                    <i class="fas fa-building" aria-hidden="true"></i>
                @endif
            </div>
            <div class="job-card__body">
                <h2 class="job-card__title">
                    <a href="{{ $vacancy->vacancy_url }}">{{ $vacancy->title }}</a>
                </h2>
                <div class="job-card__company">{{ $vacancy->partner->companyProfile->display_name ?? $vacancy->partner->name }}</div>
                <div class="job-card__tags">
                    <a class="job-card__tag" href="{{ route('job-openings', array_merge(request()->all(), ['sector_id' => $vacancy->sector_id])) }}">
                        <i class="fa fa-{{ $vacancy->sector->icon }}" aria-hidden="true"></i> {{ $vacancy->sector->name }}
                    </a>
                    <a class="job-card__tag" href="{{ route('job-openings', array_merge(request()->all(), ['job_type' => $vacancy->type])) }}">
                        <i class="fa fa-{{ $types[$vacancy->type] }}" aria-hidden="true"></i> {{ $vacancy->type }}
                    </a>
                </div>
                @if (($vacancy->description ?? '') !== '')
                    <p class="job-card__description">{{ \Illuminate\Support\Str::limit($vacancy->description, 280) }}</p>
                @endif
                <a class="home-link" href="{{ $vacancy->vacancy_url }}">
                    View the job opening <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
        </article>
    @empty
        <p class="text-muted">We could not find any job openings.</p>
    @endforelse
@endsection

@section('aside')
    <form class="news-search" action="{{ url('/career/job-openings') }}" method="GET">
        <h2 class="news-author__heading">Search for job openings</h2>
        <x-forms.text name="title" label="Title" placeholder="Search by job title" :value="$request->title()" />
        <x-forms.select name='partner_id' label="Company" :value="$request->partnerId()" :options="$partners" />
        <x-forms.select name='sector_id' label="Sector" :value="$request->sectorId()" :options="$sectors" />
        <x-forms.select
            name='job_type'
            label="Type of job"
            :value="$request->jobType()"
            :options="array_merge(['' => 'Any'], array_combine(array_keys($types), array_keys($types)))"
        />

        <button type="submit" class="btn home-btn home-btn--crimson btn-block">
            <i class="fas fa-search" aria-hidden="true"></i> Search
        </button>
    </form>
@endsection
