@extends('admin.layouts.app')

@section('title', __('offers.add_offer'))

@section('content')

<form
    method="POST"
    action="{{ route('admin.offers.store') }}">

    @csrf

    @include('admin.offers.form')

</form>

@endsection