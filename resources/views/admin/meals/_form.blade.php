<div class="mb-3">

    <label class="form-label">
        Category
    </label>

    <select
        name="category_id"
        class="form-select">

        @foreach($categories as $category)

            <option
                value="{{ $category->id }}"
                @selected(old('category_id', $meal->category_id ?? '') == $category->id)>

                {{ $category->name }}

            </option>

        @endforeach

    </select>

</div>

<div class="mb-3">

    <label class="form-label">
        Subcategory
    </label>

    <select
        name="subcategory_id"
        class="form-select">

        @foreach($subcategories as $subcategory)

            <option
                value="{{ $subcategory->id }}"
                @selected(old('subcategory_id', $meal->subcategory_id ?? '') == $subcategory->id)>

                {{ $subcategory->name }}

            </option>

        @endforeach

    </select>

</div>

<div class="mb-3">

    <label class="form-label">
        Title
    </label>

    <input
        type="text"
        name="title"
        value="{{ old('title', $meal->title ?? '') }}"
        class="form-control">

</div>

<div class="mb-3">

    <label class="form-label">
        Description
    </label>

    <textarea
        name="description"
        rows="4"
        class="form-control">{{ old('description', $meal->description ?? '') }}</textarea>

</div>

<div class="mb-3">

    <label class="form-label">
        Price
    </label>

    <input
        type="number"
        step="0.01"
        name="price"
        value="{{ old('price', $meal->price ?? '') }}"
        class="form-control">

</div>

<div class="mb-3">

    <label class="form-label">
        Stock Quantity
    </label>

    <input
        type="number"
        name="stock_quantity"
        value="{{ old('stock_quantity', $meal->stock_quantity ?? 0) }}"
        class="form-control">

</div>

<div class="mb-3">

    <label class="form-label">
        Status
    </label>

    <select
        name="is_available"
        class="form-select">

        <option value="1">Available</option>

        <option value="0">Unavailable</option>

    </select>

</div>

<button
    type="submit"
    class="btn btn-primary">

    Save

</button>