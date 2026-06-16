@extends('layout.layout')

@section('title', 'Create Meal')
@section('breadcrumb', 'Admin / Meals / Create')
@section('page-title', 'Create Meal')

@section('content')
    <x-meals.form
        :action="route('admins.meals.store')"
        :categories="$categories"
        :subcategories="$subcategories"
        submit-label="Create Meal"
    />
@endsection
@extends('layout.layout')

@section('title', 'Edit Meal')
@section('breadcrumb', 'Admin / Meals / Edit')
@section('page-title', 'Edit Meal')

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