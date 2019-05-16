@extends('layouts.app')

@section('content')
    <category-index :user-name="'{{ Auth::user()->name }}'"></category-index>
@endsection