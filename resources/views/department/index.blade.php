@extends('layouts.app')

@section('content')
    <department-index :user-name="'{{ Auth::user()->name }}'" :user-role-level="{{ Auth::user()->level() }}" :user-id="{{ Auth::user()->id }}"></department-index>
@endsection
    