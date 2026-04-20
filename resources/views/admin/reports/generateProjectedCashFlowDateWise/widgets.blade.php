<div class="card custom-card">
    <div class="card-header">
        <div class="d-flex w-100">
            <div class="d-flex align-items-center justify-content-between w-100 flex-wrap">
                <div class="me-3 mb-3">
                    <p class="text-muted mb-0">Grand Total Amount</p>
                    <p class="fw-semibold fs-16 mb-0">
                        {{ $loan_helper->formatCurrency($report['totalWeeklyInterest'] + $report['totalWeeklyEstablishmentFee'] + $report['totalPrincipalPayment'] + $report['totalRescheduleFee'] + $report['totalDishonoredFee']) }}
                    </p>
                </div>
                <div class="me-3 mb-3">
                    <p class="text-muted mb-0">Total Interest & fees</p>
                    <p class="fw-semibold fs-16 mb-0">
                        {{ $loan_helper->formatCurrency($report['totalWeeklyInterest'] + $report['totalWeeklyEstablishmentFee']) }}
                    </p>
                </div>
                
                <div class="me-3 mb-3">
                    <p class="text-muted mb-0">Total Principal</p>
                    <p class="fw-semibold fs-16 mb-0">
                        {{ $loan_helper->formatCurrency($report['totalPrincipalPayment']) }}
                    </p>
                </div>
                <div class="me-3 mb-3">
                    <p class="text-muted mb-0">Total Reschedule Fee</p>
                    <p class="fw-semibold fs-16 mb-0">{{ $loan_helper->formatCurrency($report['totalRescheduleFee']) }}
                    </p>
                </div>
                <div class="me-3 mb-3">
                    <p class="text-muted mb-0">Total Dishourned Fee</p>
                    <p class="fw-semibold fs-16 mb-0">{{ $loan_helper->formatCurrency($report['totalDishonoredFee']) }}
                    </p>
                </div>
                <div class="me-5 mb-3 d-none">
                    <p class="text-muted mb-0">Total Establishment Fee</p>
                    <p class="fw-semibold fs-16 mb-0">
                        {{ $loan_helper->formatCurrency($report['totalWeeklyEstablishmentFee']) }}
                    </p>
                </div>
                <div class="me-5 mb-3">
                    <p class="text-muted mb-0">Collection %</p>
                    <p class="fw-semibold fs-16 mb-0">
                        {{ $loan_helper->roundNumber(($report['totalCompletePayment'] / $report['totalWeeklyPayment']) * 100) }}
                        %
                    </p>
                </div>
                <div class="me-5 mb-3">
                    <p class="text-muted mb-0">Collected Amount</p>
                    <p class="fw-semibold fs-16 mb-0">
                        {{ $loan_helper->formatCurrency($report['totalCompletePayment']) }}
                    </p>
                </div>
                <div class="me-3 mb-3">
                    <p class="text-muted mb-0">Pending Amount</p>
                    <p class="fw-semibold fs-16 mb-0">
                        {{ $loan_helper->formatCurrency($report['totalPendingPayment']) }}
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
