@extends('layouts.app')

@section('content')
<report-view :user-name="'{{ Auth::user()->name }}'" :company-id="{{ $companyId }}" :location-id="{{ $locationId }}" :operationline-id="{{ $operationLineId }}" :category-id="{{ $categoryId }}" :area-id="{{ $areaId}}" :process-owner-id="{{ $processOwnerId }}"></report-view>
@endsection