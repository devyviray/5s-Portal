@extends('layouts.app')

@section('content')
    <user-index :user-name="'{{ Auth::user()->name }}'" :user-role-level="{{ Auth::user()->level() }}" :user-id="{{ Auth::user()->id }}"></user-index>
@endsection