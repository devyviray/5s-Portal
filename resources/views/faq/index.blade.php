@extends('layouts.app')

@section('content')
    <faq-index :user-name="'{{ Auth::user()->name }}'" :user-role="{{ Auth::user()->level() }}" :user-id="{{ Auth::user()->id }}"></faq-index>
@endsection