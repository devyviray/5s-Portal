@extends('layouts.app')

@section('content')
    <company-area-index :user-name="'{{ Auth::user()->name }}'" :user-role="{{ Auth::user()->level() }}" :user-id="{{ Auth::user()->id }}"></company-area-index>
@endsection