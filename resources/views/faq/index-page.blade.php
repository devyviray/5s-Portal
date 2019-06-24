@extends('layouts.app')

@section('content')
    <faq-index-page :user-name="'{{ Auth::user()->name }}'" :user-role-level="{{ Auth::user()->level() }}" :user-id="{{ Auth::user()->id }}"></faq-index-page>
@endsection