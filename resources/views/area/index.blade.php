@extends('layouts.app')

@section('content')
    <area-index :user-name="'{{ Auth::user()->name }}'" :user-role="{{ Auth::user()->level() }}"></area-index>
@endsection