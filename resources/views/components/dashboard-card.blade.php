<div class="col-md-4">
    <div class="card border-0 shadow-sm rounded-4 h-100">
        <div class="card-body">
            <span class="text-muted small text-uppercase fw-semibold">
                {{ $title }}
            </span>

            <div class="d-flex align-items-center justify-content-between mt-3">
                <h2 class="fw-bold @if($target ?? false && $result >= $target) text-success @else text-primary @endif mb-0">
                    {{ $result }}
                </h2>

                @if ($target ?? false)
                    <span class="badge bg-light text-dark border rounded-pill px-3 py-2">
                        Goal: {{ $target }}
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>
