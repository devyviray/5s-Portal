@extends('layouts.app')

@section('content')
    <report-edit :user-name="'{{ Auth::user()->name }}'" :user-role-level="{{ Auth::user()->roles()->pluck('level') }}" :report-id="{{ $reportId }}" :user-id="{{ Auth::user()->id }}"></report-edit>
@endsection