<div class="col-xl-12">
    <div class="card custom-card">
        <div class="card-header  justify-content-between">
            <div class="card-title">Affordability Summary </div>

        </div>
        <div class="card-body">
            <div class="d-flex w-100">
                <div class="d-flex align-items-center justify-content-between w-100 flex-wrap">
                    <div class="me-3">
                        <p class="fw-semibold fs-16 mb-0">
                            {{ $loan_helper->formatCurrency($summary->average_monthly_income) }}
                        </p>
                        <p class="text-muted mb-0">Average Monthly Income</p>
                    </div>
                    <div class="me-3">
                        <p class="fw-semibold fs-16 mb-0">
                            {{ $loan_helper->formatCurrency($summary->average_monthly_expenses) }}
                        </p>
                        <p class="text-muted mb-0">Average Monthly Expenses</p>
                    </div>
                    <div class="me-3">
                        <p class="fw-semibold fs-16 mb-0">{{ $loan_helper->formatCurrency($summary->surplus) }}
                        </p>
                        <p class="text-muted mb-0">Average Surplus</p>
                    </div>
                    <div class="me-3">
                        <p class="fw-semibold fs-16 mb-0">{{ $summary->no_of_active_loans }}
                        </p>
                        <p class="text-muted mb-0">Active Loans</p>
                    </div>
                    <div class="me-3">
                        <p class="fw-semibold fs-16 mb-0">
                            {{ $loan_helper->formatCurrency($summary->average_loan_debt_amount) }} (Monthly)
                        </p>
                        <p class="text-muted mb-0">Average Estimated Loan Repayment</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card custom-card">
        <div class="card-body text-dark">
            <div class="row">
             @include('admin.loans.widgets.illion.serviceAbility.widgets.incomeTable')
             @include('admin.loans.widgets.illion.serviceAbility.widgets.expensesTable')
             @include('admin.loans.widgets.illion.serviceAbility.widgets.loans')
            </div>
        </div>
    </div>
</div>
