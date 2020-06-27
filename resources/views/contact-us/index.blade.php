@extends('layouts.app')

@section('content')
    <contact-us-index :user-name="'{{ Auth::user()->name }}'" :user-role-level="{{ Auth::user()->roles()->pluck('level') }}" :user-id="{{ Auth::user()->id }}"></contact-us-index>
@endsection