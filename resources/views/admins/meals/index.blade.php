@extends('layout.layout')
@section('title', 'Meals') 
@section('breadcrumb', 'Admin / Meals') 
@section('page-title', 'Meals')
@section('page-actions') 
<a href="{{ route('admins.meals.create') }}" class="btn btn-primary"> Create Meal </a>
    @endsection 
    @section('content')
     @php 
     $columns = [ [ 'label' => 'Title', 'key' => 'title', 'link' => true, ], [ 'label' => 'Category', 'key' => 'category.name', ], [ 'label' => 'Price', 'key' => 'price', ], [ 'label' => 'Stock', 'key' => 'stock_quantity', ], [ 'label' => 'Available', 'key' => 'is_available', 'type' => 'boolean', ], ]; 
     @endphp 
     <div class="d-flex flex-column gap-3">
        <form id="bulk-delete-form" action="{{ route('admins.meals.mass-destroy') }}" method="POST"> @csrf @method('DELETE')
            <button class="btn btn-outline-danger"> Delete Selected </button> </form>
             <x-table.index :records="$meals"
            :columns="$columns" selectable checkbox-form="bulk-delete-form" show-route="admins.meals.show"
            edit-route="admins.meals.edit" delete-route="admins.meals.destroy" /> 
            {{ $meals->links() }}

</div> @endsection
