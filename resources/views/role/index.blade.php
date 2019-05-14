@extends('layouts.app')

@section('content')
    <role-index :user-name="'{{ Auth::user()->name }}'"></role-index>
@endsection