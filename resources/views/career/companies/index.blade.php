@extends('layout.one-column-layout')

@section('page-title')
    Companies
@endsection
@section('header-image-url', '/images/header/oslo.jpg')
@section('title', "Company profiles - T.F.V. ‘Professor Francken’")
@section('keywords', $keywords)

@section('content')
    <div class="row">
        @foreach ($partners as $partner)
            <div class="col-sm-6 col-lg-4 mb-4">
                <a href="{{ action([Francken\Extern\Http\CompaniesController::class, 'show'], ['partner' => $partner])}}" class="committee-tile">
                    <div class="committee-tile__logo">
                        @if ($partner->logo !== null)
                            <img alt="" src="{{ $partner->logo }}"/>
                        @else
                            <i class="fas fa-building" aria-hidden="true"></i>
                        @endif
                    </div>
                    <h2 class="committee-tile__name">{{ $partner->companyProfile->display_name }}</h2>
                </a>
            </div>
        @endforeach
    </div>
@endsection
