@extends('layouts.app')

@section('content')
    <location-index :user-name="'{{ Auth::user()->name }}'" :user-role="{{ Auth::user()->level() }}"></location-index>
@endsection