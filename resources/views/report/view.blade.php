@extends('layouts.app')

@section('content')
    <report-view :user-name="'{{ Auth::user()->name }}'" :report-id="{{ $reportId }}"></report-view>
@endsection