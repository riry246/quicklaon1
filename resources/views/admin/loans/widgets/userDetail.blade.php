@inject('loan_helper', 'App\Http\Helpers\LoanHelper')

<div class="col-xl-12">
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title ">Application Detail </div>
        </div>
        <div class="card-body">
            <div class="row gy-md-0 gy-3">
                @php
                    $cardItems = [
                        ['label' => 'Amount', 'value' => $loan_helper->formatCurrency($loanapplication['amount'])],
                        [
                            'label' => 'Duration',
                            'value' => $loanapplication['duration'] . ' weeks',
                        ],
                        [
                            'label' => 'Frequency',
                            'value' => $loanapplication['frequency'],
                        ],
                        [
                            'label' => 'Applied Date',
                            'value' => $loan_helper->formateDate($loanapplication['created_at']),
                        ],
                        ['label' => 'Application Step', 'value' => $loanapplication->applicationStep->name],
                    ];
                @endphp

                @foreach ($cardItems as $item)
                    <div class="col-xxl-2 col-xl-2 col-lg-2 col-md-2col-sm-12 mb-3">
                        <div class="d-flex align-items-top">
                            <div>
                                <span class="d-block mb-1 text-muted">{{ $item['label'] }}</span>
                                <h6 class="fw-semibold mb-0">{{ ucfirst($item['value']) }}</h6>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col-xxl-2 col-xl-2 col-lg-2 col-md-2col-sm-12 mb-3">
                    <div class="d-flex align-items-top">
                        <div>
                            <span class="d-block mb-1 text-muted">Status</span>
                            @if ($loanapplication['status'] == 'active')
                                <button
                                    class="btn btn-sm btn-success">{{ ucfirst($loanapplication['status']) }}</button>
                            @elseif($loanapplication['status'] == 'declined')
                                <button class="btn btn-sm btn-danger">{{ ucfirst($loanapplication['status']) }}</button>
                            @else
                                <button
                                    class="btn btn-sm btn-primary">{{ ucfirst($loanapplication['status']) }}</button>
                            @endif

                        </div>
                    </div>
                </div>
                <div class="col-xxl-2 col-xl-2 col-lg-2 col-md-2col-sm-12 mb-3">
                    <div class="d-flex align-items-top">
                        <div>
                            <span class="d-block mb-1 text-muted">Approved Amount</span>
                            <h6 class="fw-semibold mb-0">
                                {{ $loan_helper->formatCurrency($loanapplication['approved_amount']) ?? 0 }}
                                @if ($loanapplication['customer_confirmation'])
                                    <i class='bx bxs-check-circle text-success'></i>
                                @else
                                    <i class='bx bxs-error-alt text-warning'></i>
                                @endif


                            </h6>

                        </div>
                    </div>
                </div>
                @php
                    $cardItems = [
                        [
                            'label' => 'Assigned to',
                            'value' => $loan_helper->getUserName($loanapplication['assign_to']) ?? 'N/A',
                        ],
                        [
                            'label' => 'Reviewed By',
                            'value' => $loan_helper->getUserName($loanapplication['reviewed_by']) ?? 'N/A',
                        ],
                        [
                            'label' => 'Reviewed Date',
                            'value' => $loan_helper->formateDate($loanapplication['reviewed_date']) ?? 'N/A',
                        ],
                        [
                            'label' => 'Approved By',
                            'value' => $loan_helper->getUserName($loanapplication['approved_by']) ?? 'N/A',
                        ],
                        [
                            'label' => 'Approved Date',
                            'value' => $loan_helper->formateDate($loanapplication['approved_date']) ?? 'N/A',
                        ],
                        [
                            'label' => 'Cost of Application',
                            'value' => $loan_helper->formatCurrency($loan_helper->costOfApplication($loanapplication)) ?? 'N/A',
                        ],
                    ];
                @endphp

                @foreach ($cardItems as $item)
                    <div class="col-xxl-2 col-xl-2 col-lg-2 col-md-2col-sm-12 mb-3">
                        <div class="d-flex align-items-top">
                            <div>
                                <span class="d-block mb-1 text-muted">{{ $item['label'] }}</span>
                                <h6 class="fw-semibold mb-0">{{ ucfirst($item['value']) }}</h6>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
