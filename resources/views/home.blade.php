@extends('layouts.app')

@section('content')
    <dashboard-index :user-name="'{{ Auth::user()->name }}'" :user-role="{{ Auth::user()->level() }}"></dashboard-index>
@endsection
    