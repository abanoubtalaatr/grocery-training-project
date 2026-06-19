<div class="mb-3">

    <label class="form-label">

        {{ __('subcategories.category') }}

    </label>

    <select
        name="category_id"
        class="form-select">

        <option value="">
            {{ __('subcategories.select_category') }}
        </option>

        @foreach($categories as $id => $name)

            <option
                value="{{ $id }}"
                @selected(old('category_id', $subcategory->category_id ?? '') == $id)>

                {{ $name }}

            </option>

        @endforeach

    </select>

    @error('category_id')

        <small class="text-danger">

            {{ $message }}

        </small>

    @enderror

</div>

<div class="mb-3">

    <label class="form-label">

        {{ __('subcategories.name') }}

    </label>

    <input
        type="text"
        name="name"
        class="form-control"
        value="{{ old('name', $subcategory->name ?? '') }}">

    @error('name')

        <small class="text-danger">

            {{ $message }}

        </small>

    @enderror

</div>

<div class="mb-3">

    <label class="form-label">

        {{ __('subcategories.description') }}

    </label>

    <textarea
        name="description"
        rows="4"
        class="form-control">{{ old('description', $subcategory->description ?? '') }}</textarea>

</div>

<div class="mb-3">

    <div class="form-check">

        <input
            type="checkbox"
            name="is_active"
            value="1"
            class="form-check-input"
            @checked(old('is_active', $subcategory->is_active ?? true))>

        <label class="form-check-label">

            Active

        </label>

    </div>

</div>

<button
    type="submit"
    class="btn btn-primary">

    {{ __('subcategories.save') }}

</button>

<a
    href="{{ route('admin.subcategories.index') }}"
    class="btn btn-secondary">

    {{ __('subcategories.cancel') }}

</a>