@extends('admin.layout')
@section('page-title', 'Hoi, ' . $member->fullname)

@section('content')
    <x-admin-navigation variant="page" />
@endsection
