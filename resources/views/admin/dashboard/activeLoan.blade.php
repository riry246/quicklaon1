<div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
    <div class="card custom-card px-3">
        <div class="card-header  justify-content-between px-2 ">
            <div class="card-title text-dark"><i class="bi bi-person-check text-secondary "></i> &nbsp Current Active
                Loans</div>
        </div>
        <div class="card-body px-2">
            <div class="row align-items-center">
                <div class="col-6 pe-0 align-items-center ">
                    <p class="mb-2 fs-12"> <span
                            class="fs-25 fw-semibold text-dark lh-1 vertical-bottom mb-0">{{ $dashboard_helper->countLoan('active') }}<span
                                class="text-muted fw-normal ms-2 fs-14">/
                                {{ $dashboard_helper->countLoan('all') }} </span></span>
                        <span class="d-block fs-13 fw-normal mt-2 text-dark">Active Loans</span>
                    </p>
                </div>
                <div class="col-6 border-start right-box">
                    <div class="border-bottom">
                        <div class="flex-fill  mb-3">
                            <p class="fw-semibold fs-16 mb-0">
                                {{ $loan_helper->formatCurrency($dashboard_helper->getTotalFeesOwing() - $dashboard_helper->getTotalOverDueIntrestFeesOwing()) }}</p>
                            <p class="mb-0 text-dark fs-13">Total owing interest and monthly fees</p>
                        </div>
                    </div>

                    <div class="flex-fill mt-3">
                        <p class="fw-semibold fs-16 mb-0">
                            {{ $loan_helper->formatCurrency($dashboard_helper->getTotalLoanDisbursed()) }}</p>
                        <p class="mb-0 text-dark fs-13 ">Total Principle Disbursed</p>
                    </div>
                </div>
                <div class="col-12 ">
                    <div class="border-top">
                        <div class="flex-fill  mt-3">
                            <p class="fw-semibold fs-16 mb-0">
                                {{ $loan_helper->formatCurrency($dashboard_helper->getTotalOverDueIntrestFeesOwing()) }}
                            </p>
                            <p class="mb-0 text-dark fs-13">Total owing overdue interest</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer px-0">
            <div class="d-flex algn-items-center">
                <div class="flex-fill mb-1">
                    <p class="fw-semibold mb-0 text-dark">Extra fees owing</p>

                </div>
                <div> <span
                        class="text-success fw-semibold">{{ $loan_helper->formatCurrency($dashboard_helper->getTotalExtraFeesOwing()) }}</span>
                </div>
            </div>
            <div class="flex-fill">
                <div class="progress progress-xs">
                    <span class="mx-5 fs-12" style="position:absolute">Interest
                        ({{ $dashboard_helper->totalContractedInterestRate() }}%)</span>
                    <div class="progress-bar bg-secondary" role="progressbar"
                        style="width: {{ $dashboard_helper->totalContractedInterestRate() }}%" aria-valuenow="15"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>

        </div>
    </div>
</div>
