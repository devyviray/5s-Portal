@extends('layouts.app')

@section('content')
    <category-index :user-name="'{{ Auth::user()->name }}'" :user-role="{{ Auth::user()->level() }}"></category-index>
@endsection