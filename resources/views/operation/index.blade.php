@extends('layouts.app')

@section('content')
    <operation-line-index :user-name="'{{ Auth::user()->name }}'"></operation-line-index>
@endsection