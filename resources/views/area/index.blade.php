@extends('layouts.app')

@section('content')
    <area-index :user-name="'{{ Auth::user()->name }}'"></area-index>
@endsection