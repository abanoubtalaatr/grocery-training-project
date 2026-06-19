@extends('admin.layouts.app')

@section('title', __('support_reports.report_details'))

@section('page-title', __('support_reports.report_details'))

@section('content')

<div class="card shadow-sm">

    <div class="card-body">

        <p>

            <strong>{{ __('support_reports.user') }}:</strong>

            {{ $supportReport->user?->full_name }}

        </p>

        <p>

            <strong>{{ __('support_reports.issue_type') }}:</strong>

            {{ $supportReport->issue_type }}

        </p>

        <p>

            <strong>{{ __('support_reports.order_number') }}:</strong>

            {{ $supportReport->order_number }}

        </p>

        <p>

            <strong>{{ __('support_reports.message') }}:</strong>

        </p>

        <div class="border rounded p-3 bg-light">

            {{ $supportReport->message }}

        </div>

        <hr>

        <form
            method="POST"
            action="{{ route('admin.support-reports.in-progress', $supportReport) }}"
            class="d-inline">

            @csrf
            @method('PATCH')

            <button class="btn btn-warning">

                Mark In Progress

            </button>

        </form>

        <form
            method="POST"
            action="{{ route('admin.support-reports.resolved', $supportReport) }}"
            class="d-inline">

            @csrf
            @method('PATCH')

            <button class="btn btn-success">

                Mark Resolved

            </button>

        </form>

    </div>

</div>

@endsection