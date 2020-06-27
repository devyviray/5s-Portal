@extends('layouts.app')

@section('content')
    <home-page-index :user-name="'{{ Auth::user()->name }}'" :user-role-level="{{ Auth::user()->roles()->pluck('level') }}" :user-id="{{ Auth::user()->id }}"></home-page-index>
@endsection