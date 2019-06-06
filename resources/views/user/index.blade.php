@extends('layouts.app')

@section('content')
    <user-index :user-name="'{{ Auth::user()->name }}'" :user-role="{{ Auth::user()->level() }}"></user-index>
@endsection