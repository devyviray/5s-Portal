@extends('layouts.app')

@section('content')
    <report-view :user-name="'{{ Auth::user()->name }}'" :user-role="{{ Auth::user()->level() }}" :user-id="{{ Auth::user()->id }}" :report-id="{{ $reportId }}"></report-view>
@endsection