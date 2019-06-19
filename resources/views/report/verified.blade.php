@extends('layouts.app')

@section('content')
    <report-verified :user-name="'{{ Auth::user()->name }}'" :user-role="{{ Auth::user()->level() }}" :report-id="{{ $reportId }}" :user-id="{{ Auth::user()->id }}"></report-verified>
@endsection