@php

    if ($illionServiceAbility) {
        $expenses = json_decode($illionServiceAbility->expenses);
        $summary = json_decode($illionServiceAbility->summary);
        $income = json_decode($illionServiceAbility->income);
        $loans = json_decode($illionServiceAbility->loans);
    }

@endphp


<div class="col-xl-12">
    <div class="card custom-card">
        <div class="card-header justify-content-between">
            <div class="card-title">Serviceability</div>
            <div>
                <a
                    href="{{ route('illion.service.ability', ['id' => $loanapplication->id]) }}"class="btn btn-sm btn-secondary">Generate
                    / Update</a>
            </div>
        </div>
        <div class="card-body">
            @if ($illionServiceAbility)
                <div class="servicereport">
                    <div class="tab-content">
                        <div class="tab-pane text-muted active show" id="summary-dropdown" role="tabpanel">
                            @include('admin.loans.widgets.illion.serviceAbility.widgets.summary')
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
