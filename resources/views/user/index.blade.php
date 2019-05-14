@extends('layouts.app')

@section('content')
    <user-index :user-name="'{{ Auth::user()->name }}'"></user-index>
@endsection