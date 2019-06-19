@extends('layouts.app')

@section('content')
    <report-index :user-name="'{{ Auth::user()->name }}'" :user-role="{{ Auth::user()->level() }}" :user-id="{{ Auth::user()->id }}"></report-index>
@endsection