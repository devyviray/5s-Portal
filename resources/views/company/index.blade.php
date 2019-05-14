@extends('layouts.app')

@section('content')
    <company-index :user-name="'{{ Auth::user()->name }}'"></company-index>
@endsection