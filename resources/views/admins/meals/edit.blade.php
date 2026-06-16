
@extends('layout.layout')

@section('title', 'Edit Meal')
@section('breadcrumb', 'Admin / Meals / Edit')
@section('page-title', 'Edit Meal')

@section('page-actions')
    <a href="{{ route('admins.meals.show', $meal) }}" class="btn btn-outline-secondary">
        View Meal
    </a>
@endsection

@section('content')
    <x-meals.form
        :meal="$meal"
        :categories="$categories"
        :subcategories="$subcategories"
        :action="route('admins.meals.update', $meal)"
        method="PUT"
        submit-label="Update Meal"
    />
@endsection
