<div class="col-xl-12">
    <div class="card custom-card">
        <div class="card-header">
            <h6><span class="fs-12">Affordability Report</span> <br />Summary</h6>
        </div>
        <div class="card-body">
            <div class="row">
                @include('admin.bankDetail.affordability.summary.netPosition')
                @include('admin.bankDetail.affordability.summary.cashflow')
                @include('admin.bankDetail.affordability.summary.creditLimit')
                @include('admin.bankDetail.affordability.summary.reportDetail')
            </div>
        </div>
    </div>
</div>
@include('admin.bankDetail.affordability.accountSummary')
@include('admin.bankDetail.affordability.incomeSummary')
@include('admin.bankDetail.affordability.expensesSummary')
</div>
