@extends('layouts.app')

@section('content')
    <report-inspection-history :user-name="'{{ Auth::user()->name }}'" :user-role-level="{{ Auth::user()->level() }}" :user-id="{{ Auth::user()->id }}" :report-id="{{ $reportId }}" :user-id="{{ Auth::user()->id }}"></report-inspection-history>
@endsection