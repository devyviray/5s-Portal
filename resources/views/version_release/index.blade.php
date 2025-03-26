@extends('layouts.app')
@section('content')
    <version-release
    :user-name="'{{ Auth::user() ? Auth::user()->name : 'unauthenticated' }}'"
    :user-role-level="{{ Auth::user() ? Auth::user()->level() : 0 }}"
    :user-id="{{ Auth::user() ? Auth::user()->id : 0 }}"></version-release>
@endsection