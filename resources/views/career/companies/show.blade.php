@extends('layout.two-column-layout')

@section('page-title')
    {{ $partner->companyProfile->display_name }}
@endsection
@section('header-image-url', '/images/header/oslo.jpg')
@section('title', $partner->companyProfile->display_name . " - T.F.V. ‘Professor Francken’")

@section('content')
    <div class="committee-page">
        <div class="cms-page company-profile">
            {!! $partner->companyProfile->compiled_content !!}
        </div>

        @if ($partner->vacancies->isNotEmpty())
            <h2 class="home-heading committee-members__heading">
                Job openings at {{ $partner->companyProfile->display_name }}
            </h2>

            <ul class="committee-members">
                @foreach ($partner->vacancies as $vacancy)
                    <li class="committee-members__member">
                        <a href="{{ $vacancy->vacancy_url }}">
                            <i class="fas fa-briefcase" aria-hidden="true"></i>
                            {{ $vacancy->title }}
                        </a>
                        <span class="text-muted">({{ $vacancy->type }})</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection

@section('aside')
    @if ($partner->logo !== null)
        <div class="company-logo">
            <img src="{{ $partner->logo }}" alt="{{ $partner->companyProfile->display_name }}'s logo"/>
        </div>
    @endif

    <nav class="committee-nav">
        <h2 class="home-heading">Companies</h2>
        <ul class="list-unstyled mb-0">
            <?php $current = $partner->id; ?>
            @foreach ($partners as $otherPartner)
                <li>
                    <a
                        href="{{ action([Francken\Extern\Http\CompaniesController::class, 'show'], ['partner' => $otherPartner])}}"
                        @class(['committee-nav__link', 'is-active' => $otherPartner->id === $current])
                    >
                        {{ $otherPartner->companyProfile->display_name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>
@endsection
