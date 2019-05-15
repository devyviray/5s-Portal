@extends('layouts.app')

@section('content')
    <faq-index-page :user-name="'{{ Auth::user()->name }}'"></faq-index-page>
@endsection