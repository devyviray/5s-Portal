@extends('layouts.app')

@section('content')
    <company-index :user-name="'{{ Auth::user()->name }}'" :user-role="{{ Auth::user()->level() }}"></company-index>
@endsection