<div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
<div class="card custom-card px-3">
    <div class="card-header  justify-content-between px-2 ">
        <div class="card-title text-dark"><i class="bi bi-wallet text-secondary "></i> &nbsp Loan Overview</div>
    </div>
    <div class="card-body px-2">
        <div class="row align-items-center">
            <div class="col-6 pe-0 align-items-center ">
                <p class="mb-2 fs-12"> <span
                        class="fs-25 fw-semibold text-dark lh-1 vertical-bottom mb-0">{{ $dashboard_helper->countLoan('completed') }}<span
                            class="text-muted fw-normal ms-2 fs-14">/
                            {{ $dashboard_helper->countLoan('active') }} </span></span>
                    <span class="d-block fs-13 fw-normal mt-2 text-dark">Completed Loans</span>
                </p>
            </div>
            <div class="col-6 border-start">
                <div class="border-bottom">
                    <div class="flex-fill  mb-3">
                        <p class="fw-semibold fs-16 mb-0">
                            {{ $dashboard_helper->getActiveLoansWithCompletePaymentStatements() }}
                        </p>
                        <p class="mb-0 text-dark fs-13">Active loan with successfull payment</p>
                    </div>
                </div>
                <div class="flex-fill mt-3">
                    <p class="fw-semibold fs-16 mb-0">
                        {{ $dashboard_helper->getActiveLoansInArrearsWithDishonoredPayments() }}</p>
                    <p class="mb-0 text-dark fs-12 ">Active loan with dishonoured payments</p>
                </div>
            </div>
            <div class="col-12 ">
                <div class="border-top">
                    <div class="flex-fill  mt-3">
                        <p class="fw-semibold fs-16 mb-0">
                            {{ count($dashboard_helper->getRecoveringLoans()) }}
                        </p>
                        <p class="mb-0 text-dark fs-13">Recovering Application</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer px-0">
        <div class="d-flex algn-items-center">
            <div class="flex-fill mb-1">
                <p class="fw-semibold mb-0 text-dark">Dishonoured

                </p>

            </div>
            <div> <span
                    class="text-success fw-semibold">{{ 100 - $dashboard_helper->getDishonoredPercentage() }}%</span>
            </div>
        </div>
        <div class="flex-fill">
            <div class="progress progress-xs">
                <div class="progress-bar bg-secondary" role="progressbar"
                    style="width: {{ 100 - $dashboard_helper->getDishonoredPercentage() }}%" aria-valuenow="15"
                    aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>

    </div>
</div>
</div>
