<div class="card custom-card">
    <div class="card-body p-0">
        <div class="p-4 border-bottom border-block-end-dashed d-flex align-items-top flex-wrap gap-2">
            <div class="svg-icon-background bg-success-transparent me-3"> <svg xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24" class="svg-success">
                    <path
                        d="M11.5,20h-6a1,1,0,0,1-1-1V5a1,1,0,0,1,1-1h5V7a3,3,0,0,0,3,3h3v5a1,1,0,0,0,2,0V9s0,0,0-.06a1.31,1.31,0,0,0-.06-.27l0-.09a1.07,1.07,0,0,0-.19-.28h0l-6-6h0a1.07,1.07,0,0,0-.28-.19.29.29,0,0,0-.1,0A1.1,1.1,0,0,0,11.56,2H5.5a3,3,0,0,0-3,3V19a3,3,0,0,0,3,3h6a1,1,0,0,0,0-2Zm1-14.59L15.09,8H13.5a1,1,0,0,1-1-1ZM7.5,14h6a1,1,0,0,0,0-2h-6a1,1,0,0,0,0,2Zm4,2h-4a1,1,0,0,0,0,2h4a1,1,0,0,0,0-2Zm-4-6h1a1,1,0,0,0,0-2h-1a1,1,0,0,0,0,2Zm13.71,6.29a1,1,0,0,0-1.42,0l-3.29,3.3-1.29-1.3a1,1,0,0,0-1.42,1.42l2,2a1,1,0,0,0,1.42,0l4-4A1,1,0,0,0,21.21,16.29Z">
                    </path>
                </svg> </div>
            <div class="flex-fill">
                <h6 class="mb-2 fs-14">Amount Deposited <span class="badge bg-success fw-semibold float-end">
                        {{ count($list) }} </span> </h6>
                <div>
                    <h4 class="fs-18 fw-semibold mb-2"><span class="count-up"
                            data-count="319">{{ $loan_helper->formatCurrency($amount) }}</span></h4>
                </div>
            </div>
        </div>
    </div>
</div>
