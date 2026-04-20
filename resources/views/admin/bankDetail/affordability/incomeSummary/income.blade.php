<div class="col-xl-3">
    <div class="card custom-card">
        <div class="card-header px-0">
            <h6><i class="bi bi-coin"></i> Income Summary</h6>
        </div>
        <div class="card-body px-0">
            <ul class="list-unstyled mb-0 pt-2 crm-deals-status">
                <li class="mx-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>Average amount (monthly)</div>
                        <div class="fs-12 text-muted">$
                            {{ number_format($statements->income->summary->regularIncomeAvg) }}
                        </div>
                    </div>
                </li>
                <li class="mx-0">
                    <div class="d-flex align-items-center justify-content-between border-top pt-3">
                        <div>Total Current Year</div>
                        <div class="fs-12 text-muted">$ {{ number_format($statements->income->summary->regularIncomeYTD) }}
                        </div>
                    </div>
                </li>
                <li class="mx-0">
                    <div class="d-flex align-items-center justify-content-between border-top pt-3">
                        <div>Total Predicted Year</div>
                        <div class="fs-12 text-muted">{{ $statements->income->summary->regularIncomeYear ?? 'N/A'}}
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
