@extends('layouts.app')

@section('content')
    <company-area-index :user-name="'{{ Auth::user()->name }}'"></company-area-index>
@endsection