<div class="row">
    @if (isset($illionBankAccount[0]))
        @include('admin.loans.widgets.illion.serviceAbility.index')
    @else
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">Serviceability</div>
                    <div>
                        @if ($loanServiceAbility)
                            <a target="_blank"
                                href="{{ route('analytics.detail.view', ['user_id' => $user->basiq_user_id, 'id' => $loanapplication->id]) }}"class="btn btn-sm btn-secondary">View
                                full page<i class="ri-eye-line ms-2 d-inline-block align-middle"></i></a>
                        @endif
                        <button class="btn btn-sm btn-primary"
                            onclick="generateServiceAbility('{{ $user->basiq_user_id }}', {{ $loanapplication->id }})">Generate
                            Report<i class="ri-code-line ms-2 d-inline-block align-middle"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    @if ($loanServiceAbility)
                        <div class="servicereport">
                            @include('admin.serviceAbility.report.widgets.navTab')
                            <div class="tab-content">
                                <div class="tab-pane text-muted active show" id="summary-dropdown" role="tabpanel">
                                    @include('admin.serviceAbility.report.widgets.summary')
                                </div>

                                <div class="tab-pane text-muted" id="income-dropdown" role="tabpanel">
                                    @include('admin.serviceAbility.report.widgets.income', [
                                        'factor' => 'income',
                                    ])
                                </div>
                                @foreach ($statements->expenses as $k => $v)
                                    <div class="tab-pane text-muted"
                                        id="{{ str_replace(' ', '', strtolower($k)) }}-dropdown" role="tabpanel">
                                        @include('admin.serviceAbility.report.widgets.expenses', [
                                            'factor' => $k,
                                        ])
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    @else
                        <div class="servicereport">
                            <p class="text-change">Generating Service Ability Report</p>
                            <button class="btn btn-sm btn-primary"
                                onclick="generateServiceAbility('{{ $user->basiq_user_id }}','{{ $loanapplication->id }}')">Generate
                                Report<i class="ri-code-line ms-2 d-inline-block align-middle"></i></button>
                        </div>
                    @endif
                    <div class="process-report mt-3" style="display:none">
                        <div class="alert alert-solid-danger alert-dismissible fade error_msg show text-dark"
                            style="display:none"><button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"><i class="bi bi-x"></i></button> </div>
                        <p class="text-change">Generating Consumer Report</p>
                        <div class="progress progress-xl mb-3 progress-animate custom-progress-4" role="progressbar"
                            aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar bg-primary-gradient" style="width: 10%"></div>
                            <div class="progress-bar-label">10%</div>
                        </div>
                        <p>System is analyzing the data. Please do not close the window.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
