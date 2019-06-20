@extends('layouts.app')

@section('content')
    <home-page-index :user-name="'{{ Auth::user()->name }}'" :user-role="{{ Auth::user()->level() }}" :user-id="{{ Auth::user()->id }}"></home-page-index>
@endsection