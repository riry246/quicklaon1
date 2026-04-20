<div class="col-xl-12">
    <div class="card custom-card">
        <div class="card-header">
            <h6><span class="fs-12">Affordability Report</span> <br />Account Summary</h6>
        </div>
        <div class="card-body">
            <div class="row">
            @include('admin.bankDetail.affordability.accountSummary.cashAssets')
            @include('admin.bankDetail.affordability.accountSummary.credit')
            @include('admin.bankDetail.affordability.accountSummary.loans')
            @include('admin.bankDetail.affordability.accountSummary.external')
            </div>
        </div>
    </div>
