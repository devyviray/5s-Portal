@extends('layouts.app')

@section('content')
    <report-trend :user-name="'{{ Auth::user()->name }}'" :user-role-level="{{ Auth::user()->level() }}" :user-id="{{ Auth::user()->id }}"></report-trend>
@endsection