@extends('layouts.app')

@section('content')
    <role-index :user-name="'{{ Auth::user()->name }}'" :user-role="{{ Auth::user()->level() }}"></role-index>
@endsection