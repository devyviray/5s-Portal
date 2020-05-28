@extends('layouts.app')

@section('content')
    <report-summary :user-name="'{{ Auth::user()->name }}'" :user-role-level="{{ Auth::user()->level() }}" :user-id="{{ Auth::user()->id }}"></report-summary>
@endsection