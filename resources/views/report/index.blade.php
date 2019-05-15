@extends('layouts.app')

@section('content')
    <report-index :user-name="'{{ Auth::user()->name }}'"></report-index>
@endsection