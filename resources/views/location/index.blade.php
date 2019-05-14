@extends('layouts.app')

@section('content')
    <location-index :user-name="'{{ Auth::user()->name }}'"></location-index>
@endsection