<div class="card custom-card">
    <div class="card-header">
        <div class="d-flex w-100">
            <div class="d-flex align-items-center justify-content-between w-100 flex-wrap">


                <div class="me-3 mb-3">
                    <p class="text-muted mb-0">Total numbers of loans</p>
                    <p class="fw-semibold fs-16 mb-0">
                        {{ $report['totalLoans'] }}</p>
                </div>
                <div class="me-3 mb-3">
                    <p class="text-muted mb-0">Active loans</p>
                    <p class="fw-semibold fs-16 mb-0">{{ $report['overall_counts']['active'] }}
                    </p>
                </div>
                <div class="me-3 mb-3">
                    <p class="text-muted mb-0">Completed loans</p>
                    <p class="fw-semibold fs-16 mb-0">{{ $report['overall_counts']['completed'] }}
                    </p>
                </div>
                <div class="me-3 mb-3">
                    <p class="text-muted mb-0">Declined loans</p>
                    <p class="fw-semibold fs-16 mb-0">{{ $report['overall_counts']['declined'] }}
                    </p>
                </div>
                <div class="me-3 mb-3">
                    <p class="text-muted mb-0">Incomplete loans</p>
                    <p class="fw-semibold fs-16 mb-0">{{ $report['overall_counts']['incomplete'] }}
                    </p>
                </div>
                <div class="me-3 mb-3">
                    <p class="text-muted mb-0">Processing loans</p>
                    <p class="fw-semibold fs-16 mb-0">{{ $report['overall_counts']['processing'] }}
                    </p>
                </div>
                <div class="me-3 mb-3">
                    <p class="text-muted mb-0">Pending loans</p>
                    <p class="fw-semibold fs-16 mb-0">{{ $report['overall_counts']['pending'] }}
                    </p>
                </div>
                <div class="me-3 mb-3">
                    <p class="text-muted mb-0">Date from</p>
                    <p class="fw-semibold fs-16 mb-0">{{ $loan_helper->formateDate($date['dateForm']) }}</p>
                </div>
                <div class="me-3 mb-3">
                    <p class="text-muted mb-0">Date to</p>
                    <p class="fw-semibold fs-16 mb-0">{{ $loan_helper->formateDate($date['dateTo']) }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <p class="mb-0 mb-3 fs-20 fw-semibold lh-1"> Calculations </p>
        <p class="card-text">
            {{ $report_type->calculation_method }}
        </p>
    </div>
</div>
