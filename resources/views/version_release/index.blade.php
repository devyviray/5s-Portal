@extends('layouts.app')
@section('content')
    <version-release
    :user-name="'{{ Auth::user() ? Auth::user()->name : 'unauthenticated' }}'"
    :user-role-level="{{ Auth::user() ? Auth::user()->level() : 0 }}"
    :user-id="{{ Auth::user() ? Auth::user()->id : 0 }}"></version-release>
    @guest
        <span class="mt-4 h3 px-2 py-4">
            <a class="text-primary" href="{{ url('/home') }}"><i class="fas fa-chevron-left mr-2"></i>Return to login</a>
        </span>
    @endguest
@endsection