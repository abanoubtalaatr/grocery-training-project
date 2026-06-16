@extends('layout.layout')

@section('title', $meal->title)
@section('breadcrumb', 'Admin / Meals / Show')
@section('page-title', $meal->title)

@section('page-actions')
    <a href="{{ route('admins.meals.edit', $meal) }}" class="btn btn-primary">
        Edit
    </a>

    <a href="{{ route('admins.meals.index') }}" class="btn btn-outline-secondary">
        Back to Meals
    </a>
@endsection

@section('content')
    <div class="row g-3">
        <div class="col-lg-4">
            <x-detail.item label="Image">
                @if($meal->image_url)
                    <img src="{{ $meal->image_url }}"
                         alt="{{ $meal->title }}"
                         class="img-fluid rounded-2 border">
                @endif
            </x-detail.item>
        </div>

        <div class="col-lg-8">
            <div class="row g-3">

                <div class="col-md-6">
                    <x-detail.item label="Title" :value="$meal->title" />
                </div>

                <div class="col-md-6">
                    <x-detail.item label="Category" :value="$meal->category?->name" />
                </div>

                <div class="col-md-6">
                    <x-detail.item label="Subcategory" :value="$meal->subcategory?->name" />
                </div>

                <div class="col-md-6">
                    <x-detail.item label="Brand" :value="$meal->brand" />
                </div>

                <div class="col-md-6">
                    <x-detail.item label="Size" :value="$meal->size" />
                </div>

                <div class="col-md-6">
                    <x-detail.item label="Price" :value="$meal->price" />
                </div>

                <div class="col-md-6">
                    <x-detail.item label="Discount Price" :value="$meal->discount_price" />
                </div>

                <div class="col-md-6">
                    <x-detail.item label="Offer Title" :value="$meal->offer_title" />
                </div>

                <div class="col-md-6">
                    <x-detail.item label="Stock Quantity" :value="$meal->stock_quantity" />
                </div>

                <div class="col-md-6">
                    <x-detail.item label="Available Date" :value="$meal->available_date" />
                </div>

                <div class="col-md-6">
                    <x-detail.item label="Expiry Date" :value="$meal->expiry_date" />
                </div>

                <div class="col-md-4">
                    <x-detail.item label="Featured">
                        @if($meal->is_featured)
                            <span class="badge text-bg-success">Yes</span>
                        @else
                            <span class="badge text-bg-secondary">No</span>
                        @endif
                    </x-detail.item>
                </div>

                <div class="col-md-4">
                    <x-detail.item label="Available">
                        @if($meal->is_available)
                            <span class="badge text-bg-success">Yes</span>
                        @else
                            <span class="badge text-bg-secondary">No</span>
                        @endif
                    </x-detail.item>
                </div>

                <div class="col-md-4">
                    <x-detail.item label="Hot Meal">
                        @if($meal->is_hot)
                            <span class="badge text-bg-danger">Hot</span>
                        @else
                            <span class="badge text-bg-secondary">No</span>
                        @endif
                    </x-detail.item>
                </div>

                <div class="col-12">
                    <x-detail.item label="Description" :value="$meal->description" />
                </div>

                <div class="col-12">
                    <x-detail.item label="Features" :value="$meal->features" />
                </div>

                <div class="col-12">
                    <x-detail.item label="Includes" :value="$meal->includes" />
                </div>

                <div class="col-12">
                    <x-detail.item label="How To Use" :value="$meal->how_to_use" />
                </div>

            </div>
        </div>
    </div>
@endsection

