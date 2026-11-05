@extends('admin.layout')
@section('page-title', 'News / ' . $news->title)

@section('content')

    {!! Form::model($news, ['url' => action([\Francken\Association\News\Http\AdminNewsController::class, 'update'], ['news' => $news]), 'method' => 'put']) !!}
    <div class="row">
        <div class="col-md-8">
            @include('admin.news._form', ['action' => 'Update'])
            @include('admin.news._publish')
        </div>

        <div class="col">
            @include('admin.news._misc')
            @include('admin.news._author')
        </div>
    </div>
    {!! Form::close() !!}

@endsection
