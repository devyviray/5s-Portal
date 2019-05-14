@extends('layouts.app')

@section('content')
    <checklist-index :user-name="'{{ Auth::user()->name }}'"></checklist-index>
@endsection