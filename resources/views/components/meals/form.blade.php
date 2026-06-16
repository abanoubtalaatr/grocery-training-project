@props(['meal' => null, 'categories', 'subcategories', 'action', 'method' => 'POST', 'submitLabel' => 'Save Meal']) <form action="{{ $action }}" method="POST" enctype="multipart/form-data"> @csrf @if (!in_array(strtoupper($method), ['GET', 'POST']))
        @method($method)
    @endif
    <div class="row">
        <div class="col-lg-8"> 
            <x-form.select name="category_id" label="Category" :options="$categories->pluck('name', 'id')" :selected="$meal?->category_id" />
            <x-form.select name="subcategory_id" label="Subcategory" :options="$subcategories->pluck('name', 'id')" :selected="$meal?->subcategory_id" /> <x-form.input
                name="title" label="Title" :value="$meal?->title" /> <x-form.textarea name="description"
                label="Description" :value="$meal?->description" /> <x-form.textarea name="features" label="Features"
                :value="$meal?->features" /> <x-form.textarea name="includes" label="Includes" :value="$meal?->includes" />
            <x-form.textarea name="how_to_use" label="How To Use" :value="$meal?->how_to_use" /> </div>
        <div class="col-lg-4"> <x-form.file name="image" label="Image" :current="$meal?->image" /> <x-form.input
                name="brand" label="Brand" :value="$meal?->brand" /> <x-form.input name="size" label="Size"
                :value="$meal?->size" /> <x-form.input type="number" step="0.01" name="price" label="Price"
                :value="$meal?->price" /> <x-form.input type="number" step="0.01" name="discount_price"
                label="Discount Price" :value="$meal?->discount_price" /> <x-form.input name="offer_title" label="Offer Title"
                :value="$meal?->offer_title" /> <x-form.input type="number" name="stock_quantity" label="Stock Quantity"
                :value="$meal?->stock_quantity" /> <x-form.input type="date" name="available_date" label="Available Date"
                :value="$meal?->available_date" /> <x-form.input type="date" name="expiry_date" label="Expiry Date"
                :value="$meal?->expiry_date" /> <x-form.checkbox name="is_featured" label="Featured" :checked="$meal?->is_featured" />
            <x-form.checkbox name="is_available" label="Available" :checked="$meal?->is_available ?? true" /> <x-form.checkbox name="is_hot"
                label="Hot Meal" :checked="$meal?->is_hot" /> </div>
    </div>
    <div class="border-top pt-3"> <button class="btn btn-primary"> {{ $submitLabel }} </button> </div>
</form>
