<div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
    <div class="card custom-card px-3">
        <div class="card-header  justify-content-between px-2 ">
            <div class="card-title text-dark"><i class="bi bi-coin text-secondary "></i> &nbsp Revenue Collected</div>
        </div>
        <div class="card-body px-2">
            <div class="row align-items-center">
                <div class="col-6 pe-0 align-items-center ">
                    <p class="mb-2 fs-12">
                     <span class="fs-22 fw-semibold text-dark lh-1 vertical-bottom mb-0 dollar-sign">
                     {{ $dashboard_helper->revenueCollected() }}
                     <span
                                class="text-muted fw-normal ms-2 fs-14"><br/>/
                               $ {{ $dashboard_helper->totalExpectedRevenue() }} </span></span>
                        <span class="d-block fs-13 fw-normal mt-2 text-dark">Revenue Collected</span>
                    </p>
                </div>
                <div class="col-6 border-start right-box">
                    <div class="border-bottom">
                        <div class="flex-fill  mb-3">
                            <p class="fw-semibold fs-16 mb-0">{{ $loan_helper->formatCurrency($dashboard_helper->getTotalFeesCollected()) }}</p>
                            <p class="mb-0 text-dark fs-13">Interest / fees collected</p>
                        </div>
                    </div>
                    <div class="flex-fill mt-3">
                        <p class="fw-semibold fs-16 mb-0">{{ $loan_helper->formatCurrency($dashboard_helper->calculateFeesCollected()) }}</p>
                        <p class="mb-0 text-dark fs-13 ">Total Extra fees collected</p>
                    </div>
                </div>
                <div class="col-12 ">
                    <div class="border-top">
                        <div class="flex-fill  mt-3">
                            <p class="fw-semibold fs-16 mb-0">{{ $loan_helper->formatCurrency($dashboard_helper->getTotalFeesCollected() + $dashboard_helper->calculateFeesCollected()) }}
                            <span
                                class="text-muted fw-normal ms-2 fs-14">/
                               {{ $loan_helper->formatCurrency($dashboard_helper->getTotalExtraFeesOwing() + $dashboard_helper->getTotalFeesOwing()) }} </span>
                            </p>
                            <p class="mb-0 text-dark fs-13">Total interest & fees collected<span
                                class="text-muted fw-normal ms-2 fs-14">/ owing</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer px-0">
            <div class="d-flex algn-items-center">
                <div class="flex-fill mb-1">
                    <p class="fw-semibold mb-0 text-dark">Principle Collected</p>

                </div>
                <div> <span class="text-success fw-semibold">$ {{ $dashboard_helper->calculatePrincipleCollected() }}</span> </div>
            </div>
            <div class="flex-fill">
                <div class="progress progress-xs">
                <span class="mx-5 fs-12" style="position:absolute">Revenue collected ({{ $dashboard_helper->getRevenueCollectedRate() }}%)</span>
                    <div class="progress-bar bg-secondary" role="progressbar" style="width: {{ $dashboard_helper->getRevenueCollectedRate() }}%" aria-valuenow="15"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>

        </div>
    </div>
</div>
