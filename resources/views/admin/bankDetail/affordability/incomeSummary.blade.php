<div class="col-xl-12">
    <div class="card custom-card">
        <div class="card-header">
            <h6><span class="fs-12">Affordability Report</span> <br />Income Summary</h6>
        </div>
        <div class="card-body">
            <div class="row">
            @include('admin.bankDetail.affordability.incomeSummary.income')
           
            @include('admin.bankDetail.affordability.incomeSummary.regularIncome')
            @include('admin.bankDetail.affordability.incomeSummary.irregularIncome')
            @include('admin.bankDetail.affordability.incomeSummary.otherIncome')
            
            </div>
        </div>
    </div>
