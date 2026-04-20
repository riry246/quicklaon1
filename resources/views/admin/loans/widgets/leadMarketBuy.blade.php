@if (isset($loanapplication->leadMarketBuy->lead_id))
    <div class="row">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">Lead Market</div>
                <a
                    href="{{ route('leadmarket.bought.view', $loanapplication->leadMarketBuy->id) }}"class="btn btn-sm btn-primary">View
                    Detail</a>
            </div>
            <div class="card-body">
                <div class="row justify-content-between">
                    <div class="col-xl-12 mt-3">
                        <p class="fs-15 mb-2 me-4 fw-semibold">Lead ID:
                            <span>{{ $loanapplication->leadMarketBuy->lead_id }}</span>
                        </p>
                    </div>
                    <div class="col-xl-12 mt-2">
                        <p class="fs-15 mb-2 me-4 fw-semibold">
                            CS App ID :
                            <a target="_blank"
                                href="https://creditsense.com.au/admin/dashboard/leads/?search-appid={{ $loanapplication->leadMarketBuy->cs_app_id }}">
                                {{ $loanapplication->leadMarketBuy->cs_app_id }}
                                <i class="ri-eye-line mx-2"></i></a>
                        </p>

                    </div>
                    <div class="btn-list mt-2">
                        <div class="d-grid gap-2 mb-4 mt-4">
                            <a href="{{ route('leadmarket.bought.leadUpdate', $loanapplication->leadMarketBuy->id) }}"
                                class="btn btn-primary btn-wave" type="button">Update Lead Infromation</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
