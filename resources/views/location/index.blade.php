@extends('layouts.app')

@section('content')
    <location-index :user-name="'{{ Auth::user()->name }}'" :user-role-level="{{ Auth::user()->level() }}" :user-id="{{ Auth::user()->id }}"></location-index>
@endsection