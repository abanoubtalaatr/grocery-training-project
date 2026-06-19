@extends('admin.layouts.app')

@section('title', __('faqs.add_faq'))

@section('content')

<form
    method="POST"
    action="{{ route('admin.faqs.store') }}">

    @csrf

    @include('admin.faqs.form')

</form>

@endsection