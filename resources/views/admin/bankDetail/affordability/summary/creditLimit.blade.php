<div class="col-xl-3">
    <div class="card custom-card">
        <div class="card-header px-0">
            <h6><i class="bi bi-credit-card"></i> Credit Limits</h6>
        </div>
        <div class="card-body px-0">
            <ul class="list-unstyled mb-0 pt-2 crm-deals-status">
                <li class="mx-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="fw-bold">LIMITS</div>
                        
                    </div>
                </li>
                <li class="mx-0">
                    <div class="d-flex align-items-center justify-content-between border-top pt-3">
                        <div class="fw-bold">Total Credit Limit</div>
                        <div class="fs-12 text-muted"> {{ $loan_helper->formatCurrency($statements->main->summary->creditLimit) }} 
                            </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
