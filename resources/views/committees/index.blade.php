@extends('layout.one-column-layout')

@section('page-title')
    Committees
@endsection

@section('content')
  <div class="row">
      @foreach ($committees as $committee)
          @component('committees._committee')
          @slot('name')
          {{ $committee->name }}
          @endslot

          @slot('link')
              {{  action([\Francken\Association\Committees\Http\CommitteesController::class, 'show'], ['board' => $committee->board, 'committee' => $committee]) }}
          @endslot

          @slot('logo')
          {{ $committee->logo }}
          @endslot
          @endcomponent
      @endforeach
  </div>
 @endsection
