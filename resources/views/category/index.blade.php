@extends('layouts.app')

@section('content')
    <category-index :user-name="'{{ Auth::user()->name }}'" :user-role="{{ Auth::user()->level() }}" :user-id="{{ Auth::user()->id }}"></category-index>
@endsection