@extends('layouts.app')

@section('content')
    <faq-index :user-name="'{{ Auth::user()->name }}'" :user-role="{{ Auth::user()->level() }}"></faq-index>
@endsection