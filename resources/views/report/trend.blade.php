@extends('layouts.app')

@section('content')
    <report-trend :user-name="'{{ Auth::user()->name }}'" :user-role="{{ Auth::user()->level() }}"></report-trend>
@endsection