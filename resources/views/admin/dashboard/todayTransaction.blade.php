@php
    $percent = 0;
    $credit = $dashboard_helper->getTransactionCompletedByDateandType(date('Y-m-d'), 'Credit');
    $debit = $dashboard_helper->getTransactionCompletedByDateandType(date('Y-m-d'), 'Debit');
    $pending = $dashboard_helper->getTransactionPendingByDateandType(date('Y-m-d'));
    $dishourned = $dashboard_helper->getTransactionDishonouredByDateandType(date('Y-m-d'));
    $expected = $dashboard_helper->getExpectedClearneceofFund(date('Y-m-d'));
    $totalTransactions = $credit['totalAmountToday'] + $dishourned['totalAmountToday'];
    
    
    if ($credit['totalAmountToday'] > 0) {
        $percent = ($credit['totalAmountToday'] / $totalTransactions) * 100;
    }

@endphp

<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
    <div class="card custom-card px-3">
        <div class="card-header  justify-content-between px-2 ">
            <div class="card-title text-dark"><i class="bi bi-coin text-secondary "></i> &nbsp Today's Transaction</div>
        </div>
        <div class="card-body px-2">
            <div class="row align-items-center">
                <div class="col-6 pe-0 align-items-center py-4">
                    <p class="mb-2 fs-12">
                        <span class="fs-25 fw-semibold text-dark lh-1 vertical-bottom mb-0 dollar-sign">
                            {{ $loan_helper->formatCurrencynormal($credit['totalAmountToday']) }}
                            <span class="text-muted fw-normal ms-2 fs-14"><br />/
                                {{ $loan_helper->formatCurrency($totalTransactions) }}
                            </span></span>
                        <span class="d-block fs-13 fw-normal mt-2 text-dark">Credit Collected</span>
                    </p>
                </div>
                <div class="col-6 border-start right-box">
                    <div class="border-bottom">
                        <div class="flex-fill  mb-3">
                            <p class="fw-semibold fs-16 mb-0">
                                {{ $loan_helper->formatCurrency($debit['totalAmountToday']) }}</p>
                            <p class="mb-0 text-dark fs-13">Debit Amount</p>
                        </div>
                    </div>
                    <div class="flex-fill mt-3">
                        <p class="fw-semibold fs-16 mb-0">
                            {{ $loan_helper->formatCurrency($pending['totalAmountToday']) }}</p>
                        <p class="mb-0 text-dark fs-13 ">Pending transaction</p>
                    </div>
                </div>
                <div class="col-12 ">
                    <div class="border-top">
                        <div class="flex-fill  mt-3">
                            <p class="fw-semibold fs-16 mb-0">
                                {{ $loan_helper->formatCurrency($dishourned['totalAmountToday']) }}
                            </p>
                            <p class="mb-0 text-dark fs-13">Dishourned transaction</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 ">
                    <div class="border-top">
                        <div class="flex-fill  mt-3">
                            <p class="fw-semibold fs-16 mb-0">
                                {{ $loan_helper->formatCurrency($expected['totalAmountToday']) }}
                            </p>
                            <p class="mb-0 text-dark fs-13">Expected Fund Clearance </p>
                        </div>
                    </div>
                </div>
                <div class="col-12 ">
                    <div class="border-top">
                        <div class="flex-fill  mt-3">
                            <p class="fw-semibold fs-16 mb-0">
                                {{ $loan_helper->formatCurrency($expected['totalAmountYesterday']) }}
                            </p>
                            <p class="mb-0 text-dark fs-13">Tomorrow Expected Fund Clearance </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer px-0">
            <div class="d-flex algn-items-center">
                <div class="flex-fill mb-1">
                    <p class="fw-semibold mb-0 text-dark">Collection Percent</p>

                </div>
                <div> <span class="text-success fw-semibold">
                        {{ $loan_helper->formatCurrencynormal($percent) }}%</span> </div>
            </div>
            <div class="flex-fill">
                <div class="progress progress-xs">
                    <div class="progress-bar bg-secondary" role="progressbar" style="width: {{ $percent }}%"
                        aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
</div>
