@extends('admin.layouts.app')

@section('title', __('faqs.edit_faq'))

@section('content')

<form
    method="POST"
    action="{{ route('admin.faqs.update', $faq) }}">

    @csrf
    @method('PUT')

    @include('admin.faqs.form')

</form>

@endsection