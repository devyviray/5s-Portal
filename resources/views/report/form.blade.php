@extends('layouts.app')

@section('content')
    <report-form :user-name="'{{ Auth::user()->name }}'" :user-role="{{ Auth::user()->level() }}"></report-form>
@endsection