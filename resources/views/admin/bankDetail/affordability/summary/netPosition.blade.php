<div class="col-xl-3">
    <div class="card custom-card">
        <div class="card-header px-0">
            <h6><i class="bi bi-wallet2"></i> Net Position</h6>
        </div>
        <div class="card-body px-0">
            <ul class="list-unstyled mb-0 pt-2 crm-deals-status">
                <li class="mx-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="fw-bold">POSITION</div>
                        
                    </div>
                </li>
                <li class="mx-0">
                    <div class="d-flex align-items-center justify-content-between border-top pt-3">
                        <div>Total Cash Assets</div>
                        <div class="fs-12 text-muted">{{ $loan_helper->formatCurrency($statements->main->summary->assets) }} 
                            </div>
                    </div>
                </li>
                <li class="mx-0">
                    <div class="d-flex align-items-center justify-content-between border-top pt-3">
                        <div>Total Liabilities</div>
                        <div class="fs-12 text-muted">{{ $loan_helper->formatCurrency($statements->main->summary->liabilities) }} 
                            </div>
                    </div>
                </li>
                <li class="mx-0">
                    <div class="d-flex align-items-center justify-content-between border-top pt-3">
                        <div class="fw-bold">Total Net Position</div>
                        <div class="fs-12 text-muted">{{ $loan_helper->formatCurrency($statements->main->summary->netPosition) }} 
                            </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
