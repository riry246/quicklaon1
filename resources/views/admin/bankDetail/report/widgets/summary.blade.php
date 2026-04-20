<div class="card custom-card">
    <div class="card-body text-dark">
        <div class="d-flex w-100">
            <div class="d-flex align-items-center justify-content-between w-100 flex-wrap">
                @foreach ($metrics as $k => $v)
                    @if ($k == 'Summary')
                        @foreach ($v as $e)
                            <div class="me-3">
                                <p class="fw-semibold fs-16 mb-0">{{ $loan_helper->formatCurrency($e->result->value) }}
                                </p>
                                <p class="text-muted mb-0">{{ $e->title }}</p>
                            </div>
                        @endforeach
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
