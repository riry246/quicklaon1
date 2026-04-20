<div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
    <div class="card custom-card px-3">
        <div class="card-header  justify-content-between px-2 ">
            <div class="card-title text-dark"><i class="bi bi-wallet text-secondary "></i> &nbsp Monoova <span
                    class="fs-12">( {{ $moonova['financials']['feeAccountNumber'] }} )</span></div>
            <div class="dropdown">
                <a aria-label="anchor" href="javascript:void(0);" class="btn btn-icon btn-sm btn-light"
                    data-bs-toggle="dropdown">
                    <i class="fe fe-more-vertical"></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" target="_blank" href="https://payments.monoova.com/">Go to Monoova</a></li>

                </ul>
            </div>
        </div>
        <div class="card-body px-2">
            <div class="row align-items-center">
                <div class="col-6 pe-0 align-items-center ">
                    <p class="mb-2 fs-12">
                        <span class="fs-25 fw-semibold text-dark lh-1 vertical-bottom mb-0 dollar-sign">
                            {{ $loan_helper->formatCurrencynormal($moonova['financials']['clearedFundsAccountFinancials']['availableBalance']) }}
                        </span>
                        <span class="d-block fs-13 fw-normal mt-2 text-dark">Available Balance</span>
                    </p>
                </div>
                <div class="col-6 border-start right-box">
                    <div class="border-bottom">
                        <div class="flex-fill  mb-3">
                            <p class="fw-semibold fs-16 mb-0">
                                {{ $loan_helper->formatCurrency($moonova['financials']['clearedFundsAccountFinancials']['unclearedFunds']) }}
                            </p>
                            <p class="mb-0 text-dark fs-13">Uncleared Funds (Pending)</p>
                        </div>
                    </div>
                    <div class="flex-fill mt-3">
                        <p class="fw-semibold fs-16 mb-0">
                            {{ $loan_helper->formatCurrency($moonova['financials']['feeAccountActualBalance']) }}</p>
                        <p class="mb-0 text-dark fs-12 ">Fee Account Balance</p>
                    </div>
                </div>
                <div class="col-12 ">
                    <div class="border-top">
                        <div class="flex-fill  mt-3">
                            <p class="fw-semibold fs-16 mb-0">
                                {{ $loan_helper->formatCurrency($dashboard_helper->getTotalLending()) }}
                            </p>
                            <p class="mb-0 text-dark fs-13">Total Transfer from CashFaster Lending Account</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer px-0">
            <div class="d-flex algn-items-center">
                <div class="flex-fill mb-1">
                    <p class="fw-semibold mb-0 text-dark">Npp Payout
                        ({{ ($moonova['financials']['nppBalance'] / $moonova['financials']['nppPayoutLimit']) * 100 }}%)
                    </p>

                </div>
                <div> <span
                        class="text-success fw-semibold">{{ $loan_helper->formatCurrency($moonova['financials']['nppBalance']) }}</span>
                </div>
            </div>
            <div class="flex-fill">
                <div class="progress progress-xs">
                    <div class="progress-bar bg-secondary" role="progressbar"
                        style="width: {{ ($moonova['financials']['nppBalance'] / $moonova['financials']['nppPayoutLimit']) * 100 }}%"
                        aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>

        </div>
    </div>
</div>
