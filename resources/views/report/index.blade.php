@extends('layouts.app')

@section('content')
<report-index :user-name="'{{ Auth::user()->name }}'" :user-role-level="{{ Auth::user()->roles()->pluck('level') }}" :user-id="{{ Auth::user()->id }}" :company-id="{{ $company }}" :location-id="{{ $location }}"></report-index>
@endsection