@php
    $costs = $dashboard_helper->calculateCosts();
@endphp
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
    <div class="card custom-card px-3">
        <div class="card-header  justify-content-between px-2 ">
            <div class="card-title text-dark"><i class="bi bi-person-check text-secondary "></i> &nbsp Illion Cost</div>
        </div>
        <div class="card-body px-2">
            <div class="row align-items-center">
                <div class="col-6 pe-0 align-items-center ">
                    <p class="mb-2 fs-12"> <span
                            class="fs-20 fw-semibold text-dark lh-1 vertical-bottom mb-0">{{ $loan_helper->formatCurrency($costs['lifetime']) }}</span>
                        <span class="d-block fs-13 fw-normal mt-2 text-dark">Life Time</span>
                    </p>
                </div>
                <div class="col-6 border-start right-box">
                    <div class="border-bottom">
                        <div class="flex-fill  mb-3">
                            <p class="fw-semibold fs-16 mb-0">
                                {{ $loan_helper->formatCurrency($costs['current_month']) }}</p>
                            <p class="mb-0 text-dark fs-13">Current Month</p>
                        </div>
                    </div>

                    <div class="flex-fill mt-3 mb-3">
                        <p class="fw-semibold fs-16 mb-0">
                            {{ $loan_helper->formatCurrency($costs['last_month']) }}</p>
                        <p class="mb-0 text-dark fs-13 ">Last Month</p>
                    </div>
                </div>
                <div class="col-12 ">
                    <div class="border-top">
                        <div class="flex-fill  mt-3 mb-3">
                            <p class="fw-semibold fs-16 mb-0">
                                {{ $loan_helper->formatCurrency($costs['today']) }}
                            </p>
                            <p class="mb-0 text-dark fs-13">Today</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 ">
                    <div class="border-top">
                        <div class="flex-fill  mt-3">
                            <p class="fw-semibold fs-16 mb-0">
                                {{ $loan_helper->formatCurrency($costs['yesterday']) }}
                            </p>
                            <p class="mb-0 text-dark fs-13">Yesterday</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
