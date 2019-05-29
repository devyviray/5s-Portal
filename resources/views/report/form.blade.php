@extends('layouts.app')

@section('content')
    <report-form :user-name="'{{ Auth::user()->name }}'"></report-form>
@endsection