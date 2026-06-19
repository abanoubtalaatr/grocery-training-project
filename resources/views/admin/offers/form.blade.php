<div class="card shadow-sm">

    <div class="card-body">

        <div class="mb-3">
            <label>{{ __('offers.title_label') }}</label>
            <input
                type="text"
                name="title"
                class="form-control"
                value="{{ old('title', $offer->title ?? '') }}">
        </div>

        <div class="mb-3">
            <label>{{ __('offers.code') }}</label>
            <input
                type="text"
                name="code"
                class="form-control"
                value="{{ old('code', $offer->code ?? '') }}">
        </div>

        <div class="mb-3">
            <label>{{ __('offers.description') }}</label>
            <textarea
                name="description"
                class="form-control">{{ old('description', $offer->description ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label>{{ __('offers.type') }}</label>

            <select
                name="type"
                class="form-select">

                <option value="percentage">
                    Percentage
                </option>

                <option value="fixed">
                    Fixed
                </option>

            </select>

        </div>

        <div class="mb-3">
            <label>{{ __('offers.discount') }}</label>
            <input
                type="number"
                step="0.01"
                name="discount_value"
                class="form-control"
                value="{{ old('discount_value', $offer->discount_value ?? '') }}">
        </div>

        <div class="mb-3">
            <label>{{ __('offers.minimum_purchase') }}</label>
            <input
                type="number"
                step="0.01"
                name="minimum_purchase"
                class="form-control"
                value="{{ old('minimum_purchase', $offer->minimum_purchase ?? '') }}">
        </div>

        <div class="mb-3">
            <label>{{ __('offers.start_date') }}</label>
            <input
                type="date"
                name="start_date"
                class="form-control"
                value="{{ old('start_date', optional($offer->start_date ?? null)->format('Y-m-d')) }}">
        </div>

        <div class="mb-3">
            <label>{{ __('offers.end_date') }}</label>
            <input
                type="date"
                name="end_date"
                class="form-control"
                value="{{ old('end_date', optional($offer->end_date ?? null)->format('Y-m-d')) }}">
        </div>

        <button class="btn btn-primary">
            {{ __('offers.save') }}
        </button>

    </div>

</div>