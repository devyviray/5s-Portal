@extends('layouts.app')

@section('content')
    <faq-index :user-name="'{{ Auth::user()->name }}'" :user-role-level="{{ Auth::user()->roles()->pluck('level') }}" :user-id="{{ Auth::user()->id }}"></faq-index>
@endsection