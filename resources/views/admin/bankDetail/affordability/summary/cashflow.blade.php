<div class="col-xl-3">
    <div class="card custom-card">
        <div class="card-header px-0">
            <h6><i class="bi bi-piggy-bank"></i> Cash Flow</h6>
        </div>
        <div class="card-body px-0">
            <ul class="list-unstyled mb-0 pt-2 crm-deals-status">
                <li class="mx-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="fw-bold">MONTHLY</div>

                    </div>
                </li>
                <li class="mx-0">
                    <div class="d-flex align-items-center justify-content-between border-top pt-3">
                        <div>Regular Income</div>
                        <div class="fs-12 text-muted">
                            {{ $loan_helper->formatCurrency($statements->main->summary->regularIncome->previous3Months->avgMonthly) }}
                        </div>
                    </div>
                </li>
                <li class="mx-0">
                    <div class="d-flex align-items-center justify-content-between border-top pt-3">
                        <div>Expenses - Payment Total</div>
                        <div class="fs-12 text-muted"> {{ $loan_helper->formatCurrency($statements->main->summary->expenses) }}
                        </div>
                    </div>
                </li>
                <li class="mx-0">
                    <div class="d-flex align-items-center justify-content-between border-top pt-3">
                        <div class="fw-bold">Change in Savings Balance</div>
                        <div class="fs-12 text-muted"> {{ $loan_helper->formatCurrency($statements->main->summary->savings) }}
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
