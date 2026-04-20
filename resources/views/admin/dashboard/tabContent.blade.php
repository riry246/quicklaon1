<div class="col-xl-12">
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">
                Transactions
            </div>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs tab-style-1 d-sm-flex d-block" role="tablist">
                <li class="nav-item active">
                    <a class="nav-link active" data-bs-toggle="tab" role="tab" href="#upcoming"
                        aria-selected="false">Upcoming Payments</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" role="tab" href="#defaulters"
                        aria-selected="false">Defaulters</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" role="tab" href="#outstanding"
                        aria-selected="false">Excessive Outstanding</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" role="tab" href="#highRiskAccount"
                        aria-selected="false">No Successful Debit</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" role="tab" href="#recoveringAccount"
                        aria-selected="false">Recovering Accounts</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" role="tab" href="#returningCustomer"
                        aria-selected="false">Returning Customers</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane text-muted show active" id="upcoming" role="tabpanel">
                    @include('admin.dashboard.upcomingPayments')
                </div>
                <div class="tab-pane text-muted" id="defaulters" role="tabpanel">
                     @include('admin.dashboard.dishournedCustomer')
                </div>
                <div class="tab-pane text-muted" id="outstanding" role="tabpanel">
                     @include('admin.dashboard.excessiveOutstanding')
                </div>
                <div class="tab-pane text-muted" id="highRiskAccount" role="tabpanel">
                     @include('admin.dashboard.highRiskAccount')
                </div>
                <div class="tab-pane text-muted" id="recoveringAccount" role="tabpanel">
                     @include('admin.dashboard.recoveringAccount')
                </div>
                <div class="tab-pane text-muted" id="returningCustomer" role="tabpanel">
                     @include('admin.dashboard.returningCustomer')
                </div>
            </div>
        </div>
    </div>
</div>
