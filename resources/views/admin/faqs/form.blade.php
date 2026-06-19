<div class="card shadow-sm">

    <div class="card-body">

        <div class="mb-3">

            <label>
                {{ __('faqs.question') }}
            </label>

            <input
                type="text"
                name="question"
                class="form-control"
                value="{{ old('question', $faq->question ?? '') }}">

        </div>

        <div class="mb-3">

            <label>
                {{ __('faqs.answer') }}
            </label>

            <textarea
                rows="5"
                name="answer"
                class="form-control">{{ old('answer', $faq->answer ?? '') }}</textarea>

        </div>

        <div class="mb-3">

            <label>
                {{ __('faqs.category') }}
            </label>

            <input
                type="text"
                name="category"
                class="form-control"
                value="{{ old('category', $faq->category ?? '') }}">

        </div>

        <div class="mb-3">

            <label>
                {{ __('faqs.order') }}
            </label>

            <input
                type="number"
                name="order"
                class="form-control"
                value="{{ old('order', $faq->order ?? 0) }}">

        </div>

        <div class="form-check mb-3">

            <input
                type="checkbox"
                name="is_active"
                value="1"
                class="form-check-input"
                @checked(old('is_active', $faq->is_active ?? true))>

            <label class="form-check-label">

                {{ __('faqs.active') }}

            </label>

        </div>

        <button
            class="btn btn-primary">

            {{ __('faqs.save') }}

        </button>

    </div>

</div>