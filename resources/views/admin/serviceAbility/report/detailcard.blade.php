<div class="col-xl-12">
    @include('admin.serviceAbility.report.widgets.reportDetail')
    <div class="card-body">
        @include('admin.serviceAbility.report.widgets.navTab')
        <div class="tab-content">
            <div class="tab-pane text-muted active show" id="summary-dropdown" role="tabpanel">
                @include('admin.serviceAbility.report.widgets.summary')
            </div>

            <div class="tab-pane text-muted" id="income-dropdown" role="tabpanel">
                @include('admin.serviceAbility.report.widgets.income', ['factor' => 'income'])
            </div>
             @foreach ($statements->expenses as $k => $v)
            <div class="tab-pane text-muted" id="{{ str_replace(' ', '', strtolower($k)) }}-dropdown" role="tabpanel">
                @include('admin.serviceAbility.report.widgets.expenses', ['factor' => $k])
            </div>
            @endforeach
            

        </div>
    </div>

</div>

