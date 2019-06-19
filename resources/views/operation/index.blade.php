@extends('layouts.app')

@section('content')
    <operation-line-index :user-name="'{{ Auth::user()->name }}'" :user-role="{{ Auth::user()->level() }}" :user-id="{{ Auth::user()->id }}"></operation-line-index>
@endsection