@extends('layouts.app')

@section('content')
    <faq-index :user-name="'{{ Auth::user()->name }}'"></faq-index>
@endsection