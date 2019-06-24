@extends('layouts.app')

@section('content')
    <area-index :user-name="'{{ Auth::user()->name }}'" :user-role-level="{{ Auth::user()->level() }}" :user-id="{{ Auth::user()->id }}"></area-index>
@endsection 