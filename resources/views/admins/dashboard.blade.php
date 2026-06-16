@extends('layout.layout')

@section('title', 'Admin Dashboard')
@section('breadcrumb', 'Admin')
@section('page-title', 'Dashboard')

@section('page-actions')
    <a href="{{ url('/') }}" class="btn btn-outline-secondary">View Site</a>
@endsection

@section('content')
    <div class="d-flex flex-column gap-3">
        <div>
            <h2 class="h4 fw-bold mb-1">Welcome back</h2>
            <p class="text-secondary mb-0">Use the sidebar to browse the application controllers.</p>
        </div>

        <div class="row g-4">
            <x-dashboard-card title="Users" :result="$usersCount" />
            <x-dashboard-card title="Meals" :result="$mealsCount" :target="$target" />
            <x-dashboard-card title="Reviews" :result="$reviewsCount" />
            {{-- <x-dashboard-card title="Status" :result="'Active'" /> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
