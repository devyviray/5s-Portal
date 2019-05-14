@extends('layouts.app')

@section('content')
    <department-index :user-name="'{{ Auth::user()->name }}'"></department-index>
@endsection
    