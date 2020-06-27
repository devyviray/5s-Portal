@extends('layouts.app')

@section('content')
    <report-view :user-name="'{{ Auth::user()->name }}'" :user-role-level="{{ Auth::user()->roles()->pluck('level') }}" :user-id="{{ Auth::user()->id }}" :report-id="{{ $reportId }}" :user-id="{{ Auth::user()->id }}"></report-view>
@endsection