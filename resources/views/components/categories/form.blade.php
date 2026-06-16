@props([
    'category' => null,
    'action',
    'method' => 'POST',
    'submitLabel' => 'Save Category',
])

<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf

    @if (! in_array(strtoupper($method), ['GET', 'POST'], true))
        @method($method)
    @endif

    <div class="row">
        <div class="col-lg-8">
            <x-form.input
                name="name"
                label="Name"
                :value="$category?->name"
                required
            />

            <x-form.input
                name="slug"
                label="Slug"
                :value="$category?->slug"
                help="Leave empty to generate from the category name."
            />

            <x-form.textarea
                name="description"
                label="Description"
                :value="$category?->description"
                rows="5"
            />
        </div>

        <div class="col-lg-4">
            <x-form.file
                name="image"
                label="Image"
                :current="$category?->image"
                help="Upload a category image."
                accept="image/*"
            />

            <x-form.input
                name="sort_order"
                label="Sort Order"
                type="number"
                :value="$category?->sort_order ?? 0"
            />

            <x-form.checkbox
                name="is_active"
                label="Active"
                :checked="$category?->is_active ?? true"
            />
        </div>
    </div>

    <div class="d-flex align-items-center gap-2 border-top pt-3">
        <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
        <a href="{{ route('admins.categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>
