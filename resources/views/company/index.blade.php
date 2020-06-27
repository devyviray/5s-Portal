@extends('layouts.app')

@section('content')
    <company-index :user-name="'{{ Auth::user()->name }}'" :user-role-level="{{ Auth::user()->roles()->pluck('level') }}" :user-id="{{ Auth::user()->id }}"></company-index>
@endsection