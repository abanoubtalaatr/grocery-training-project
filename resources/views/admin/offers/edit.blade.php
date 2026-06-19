@extends('admin.layouts.app')

@section('title', __('offers.edit_offer'))

@section('content')

<form
    method="POST"
    action="{{ route('admin.offers.update', $offer) }}">

    @csrf
    @method('PUT')

    @include('admin.offers.form')

</form>

@endsection