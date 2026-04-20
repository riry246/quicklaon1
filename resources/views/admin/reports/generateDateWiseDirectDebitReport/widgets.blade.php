<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
    <div class="card custom-card px-3">
        <div class="card-header  justify-content-between px-2 ">
            <div class="card-title text-dark"><i class="bi bi-person-check text-secondary "></i> &nbsp Direct Debit
                <br /><i class="bi bi-calendar text-secondary "></i> &nbsp
                ({{ $loan_helper->formateDate($date['dateForm']) }} -
                {{ $loan_helper->formateDate($date['dateTo']) }})
            </div>
        </div>
        <div class="card-body px-2">
            <div class="row align-items-center">
                <div class="col-6 pe-0 align-items-center ">
                    <p class="mb-2 fs-12"> <span
                            class="fs-25 fw-semibold text-dark lh-1 vertical-bottom mb-0">{{ $loan_helper->formatCurrency($report['grandTotalAmount']['success']) }}<span
                                class="text-muted fw-normal ms-2 fs-14"><br />/
                                {{ $loan_helper->formatCurrency($report['grandTotalAmount']['total']) }}</span></span>
                        <span class="d-block fs-13 fw-normal mt-2 text-dark">Total Amount</span>
                    </p>
                </div>
                <div class="col-6 border-start right-box">
                    <div class="border-bottom">
                        <div class="flex-fill  mb-3">
                            <p class="fw-semibold fs-16 mb-0">
                                {{ $loan_helper->formatCurrency($report['grandTotalAmount']['dishonoured']) }}</p>
                            <p class="mb-0 text-dark fs-13">Total Dishonoured amount</p>
                        </div>
                    </div>

                    <div class="flex-fill my-3">
                        <p class="fw-semibold fs-16 mb-0">
                            {{ $loan_helper->formatCurrency($report['grandTotalAmount']['pending']) }}
                        </p>
                        <p class="mb-0 text-dark fs-13 ">Total Pending Trasnaction</p>
                    </div>
                </div>
                <div class="col-12 ">
                    <div class="border-top">
                        <div class="flex-fill  my-3">
                            <p class="text-muted mb-0">Moonova Fee amount including GST</p>
                            <p class="fw-semibold fs-16 mb-0">
                                {{ $loan_helper->formatCurrency($report['grandTotalAmount']['transactionCharge']) }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="card-footer px-0">
            <div class="d-flex algn-items-center">
                <div class="flex-fill mb-1">
                    <p class="fw-semibold mb-0 text-dark">Collection Percentage</p>

                </div>
                <div> <span
                        class="text-success fw-semibold">{{ $loan_helper->roundNumber($report['grandTotalAmount']['percentage']) }}
                        %</span>
                </div>
            </div>
            <div class="flex-fill">
                <div class="progress progress-xs">
                    <div class="progress-bar bg-secondary" role="progressbar"
                        style="width:{{ $loan_helper->roundNumber($report['grandTotalAmount']['percentage']) }}%"
                        aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
</div>
