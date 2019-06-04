@extends('layouts.app')

@section('content')
    <report-verified :user-name="'{{ Auth::user()->name }}'" :report-id="{{ $reportId }}"></report-verified>
@endsection