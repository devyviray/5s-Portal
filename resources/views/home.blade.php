@extends('layouts.app')

@section('content')
    <dashboard-index :user-name="'{{ Auth::user()->name }}'"></dashboard-index>
@endsection
    