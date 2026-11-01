@extends('layout.one-column-layout')

@section('page-title')
    Page not found
@endsection

@section('header-image-url', '/images/header/oslo.jpg')
@section('title', "404 - Page not found - T.F.V. 'Professor Francken'")

@section('header-image')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 text-center">
            <canvas id='mpcCanvas' width="1600px" height="680px" style="
                        background-color: #fafafa;

                        border: thin solid #fefefe;
                        width: 100%;
                        border-radius: 25%;


                        ">
            </canvas>
        </div>
    </div>

    <script src="/js/404.js"></script>
@endsection
