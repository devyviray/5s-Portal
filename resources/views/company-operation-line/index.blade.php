@extends('layouts.app')

@section('content')
    <company-operation-line-index :user-name="'{{ Auth::user()->name }}'" :user-role-level="{{ Auth::user()->level() }}" :user-id="{{ Auth::user()->id }}"></company-operation-line-index>
@endsection