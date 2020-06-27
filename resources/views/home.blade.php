@extends('layouts.app')

@section('content')
    <dashboard-index :user-name="'{{ Auth::user()->name }}'" :user-role-level="{{ Auth::user()->roles()->pluck('level') }}" :user-id="{{ Auth::user()->id }}"></dashboard-index>
@endsection
    