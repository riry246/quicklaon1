@inject('loan_helper', 'App\Http\Helpers\LoanHelper')
<div class="col-xxl-12 col-xl-12 col-md-12">
    <div class="card custom-card">
        <div class="card-header d-sm-flex d-block">
            <div class="card-title"><i class="bi bi-graph-up-arrow text-secondary"></i> &nbsp Bad Debts / Write Offs
                Analysis</div>
            <div class="tab-menu-heading border-0 p-0 ms-auto mt-sm-0 mt-2">
            </div>
            <div class="mt-sm-0 mt-2">
                <div class="input-group">
                    <div class="input-group-text text-muted"> <i class="ri-calendar-line"></i> </div>
                    <input type="text" class="form-control flatpickr-input active" id="daterange"
                        placeholder="Date range picker" readonly="readonly">
                </div>
            </div>
        </div>
        <div class="card-body p-3" id="showBadDebt">

        </div>
        <div class="card-footer border-top-0">

        </div>
    </div>
</div>
