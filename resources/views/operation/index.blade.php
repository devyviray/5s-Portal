@extends('layouts.app')

@section('content')
    <operation-line-index :user-name="'{{ Auth::user()->name }}'" :user-role-level="{{ Auth::user()->roles()->pluck('level') }}" :user-id="{{ Auth::user()->id }}"></operation-line-index>
@endsection