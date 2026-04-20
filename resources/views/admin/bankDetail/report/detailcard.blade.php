<div class="col-xl-12">
    @include('admin.bankDetail.report.widgets.reportDetail')
    <div class="card-body">
        @include('admin.bankDetail.report.widgets.navTab')
        <div class="tab-content">
            <div class="tab-pane text-muted active show" id="summary-dropdown" role="tabpanel">
                @include('admin.bankDetail.report.widgets.summary')
                @include('admin.bankDetail.report.widgets.userDetail')
                @include('admin.bankDetail.report.widgets.accountSummary')
                @include('admin.bankDetail.report.widgets.metric')
            </div>
            <div class="tab-pane text-muted" id="income-dropdown" role="tabpanel">
                @include('admin.bankDetail.report.widgets.income')
            </div>
            @foreach ($group as $k => $v)
                @if ($k == 'Income')
                @else
                    <div class="tab-pane text-muted" id="{{ str_replace(' ', '', strtolower($k)) }}-dropdown" role="tabpanel">
                        @include('admin.bankDetail.report.widgets.expenses'  , ['expensesname'=>$k])
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
