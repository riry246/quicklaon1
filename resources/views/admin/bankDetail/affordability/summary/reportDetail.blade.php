<div class="col-xl-3">
    <div class="card custom-card">
        <div class="card-header px-0">
            <h6><i class="bi bi-clipboard-data"></i> Report Detail</h6>
        </div>
        <div class="card-body px-0">
            <ul class="list-unstyled mb-0 pt-2 crm-deals-status">
                <li class="mx-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>ID</div>
                        <div class="fs-10 text-muted">{{ $statements->main->id }}</div>
                    </div>
                </li>
                <li class="mx-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>Period</div>
                        <div class="fs-12 text-muted">{{ $loan_helper->formateDateDynamic($statements->main->fromMonth, 'M-Y') }} to {{ $loan_helper->formateDateDynamic($statements->main->toMonth, 'M-Y') }} 
                            </div>
                    </div>
                </li>
                <li class="mx-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>Coverage</div>
                        <div class="fs-12 text-muted">{{ $statements->main->coverageDays }} days</div>
                    </div>
                </li>
                <li class="mx-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>Created</div>
                        <div class="fs-12 text-muted">{{ $loan_helper->formateDateTime($statements->main->generatedDate) }}</div>
                    </div>
                </li>
               
            </ul>
        </div>
    </div>
</div>
