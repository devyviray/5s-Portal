@extends('layouts.app')

@section('content')
    <checklist-index :user-name="'{{ Auth::user()->name }}'" :user-role="{{ Auth::user()->level() }}"></checklist-index>
@endsection