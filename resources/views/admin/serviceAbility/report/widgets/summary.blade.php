@php
    $score = 0;
    $firstTransactionDate = null;
    $totalIncome = $summary['income']['summary']['total'];
    $totalexpenses = $summary['expenses']['summary']['total'];
    $averageIncome = $summary['income']['summary']['total'] / 12;
    $averageexpenses = $summary['expenses']['summary']['total'] / 12;
    $surplus = $averageIncome - (float) str_replace(['-'], '', trim($averageexpenses));
    $salaryMessage = '';
    $loanRepaymentsmsg = '';

    if ($surplus > 0) {
        $score += 1;
        $surplusMessage = 'Customer has a surplus in their finances.';
    } else {
        $surplusMessage = 'Customer has a deficit in their finances.';
    }

    if ($noOfLoans < 2) {
        $score += 1;
        $loanMessage = 'Customer has less than 2 loans.';
    } else {
        $loanMessage = 'Customer has 2 or more loans.';
    }

    $creditScore = $latestcreditScore->score_value ?? 0; // Example value, replace it with your actual logic to get the score
    $credirScoremessage = '';

    if ($creditScore >= 800 && $creditScore <= 850) {
        $credirScoremessage = 'Excellent';
        $score += 1;
    } elseif ($creditScore >= 740 && $creditScore <= 799) {
        $credirScoremessage = 'Very Good';
        $score += 1;
    } elseif ($creditScore >= 670 && $creditScore <= 739) {
        $credirScoremessage = 'Good';
        $score += 1;
    } elseif ($creditScore >= 580 && $creditScore <= 669) {
        $credirScoremessage = 'Fair';
        $score += 1;
    } elseif ($creditScore >= 300 && $creditScore <= 579) {
        $credirScoremessage = 'Poor';
    } else {
        $credirScoremessage = 'Credit score not available';
    }

    //Last salary date
    $latestTransactionDate = null;
    foreach ($statements->income as $k => $v) {
        if ($k == 'Salary') {
            foreach ($v->subgroup as $s) {
                foreach ($s->transactions as $t) {
                    $transactionDate = $t->date;

                    // Check if the current transaction date is later than the latest transaction date
                    if ($transactionDate > $latestTransactionDate || $latestTransactionDate === null) {
                        $latestTransactionDate = $transactionDate;
                    }
                }
            }
        }
    }

    $today = new DateTime();
    $transactionDate = new DateTime($latestTransactionDate);

    // Calculate the difference in days between today and the transaction date
    $interval = $today->diff($transactionDate);
    $daysDifference = $interval->days;

    if ($daysDifference > 60) {
        $salaryMessage =
            'Customer has not been paid since ' .
            $daysDifference .
            ' days. This could indicate a problem with their employment.';
    } else {
        $salaryMessage = 'Customer has been paid regularly.';
        $score += 1;
    }

    //loanRepayments
    if (abs($loanRepayments) > 10) {
        $loanRepaymentsmsg =
            'Customer has old pending loans to settle and has to pay ' .
            $loan_helper->formatCurrency(abs($loanRepayments)) .
            ' this month.';
    } else {
        $score += 1;
        $loanRepaymentsmsg = 'Customer does not have significant pending loans to settle this month.';
    }

    //Income calcualtion
    $loanamount = $loanapplication->amount;
    $duration = $loanapplication->duration;
    $loanamountAfterEst = ($loanamount * 20) / 100 + $loanamount;
    if ($duration == '4') {
        $totalLoan = ($loanamount * 4) / 100 + $loanamountAfterEst;
    } elseif ($duration == '8') {
        $totalLoan = ($loanamount * 8) / 100 + $loanamountAfterEst;
    } else {
        $totalLoan = ($loanamount * 12) / 100 + $loanamountAfterEst;
    }

    $repayment = ($totalLoan / $duration) * 4;

    $loanAmountrecom = ($surplus * 3) / $duration;

    if ($loanAmountrecom < 0) {
        $loanAmountrecom = '0';
    } elseif ($loanAmountrecom < 300) {
        $loanAmountrecom = '300';
    } elseif ($loanAmountrecom >= 300 && $loanAmountrecom < 1000) {
        $loanAmountrecom = '500';
    } else {
        $loanAmountrecom = '2000';
    }

    echo $loanAmountrecom;

    if ($surplus >= $repayment) {
        $error = false;
        $approvemessage = 'Based on financial situation, loan can be approved.';
    } else {
        $error = true;
        $approvemessage = 'Loan Amount of $' . $loanamount . ' cannot be approved at this time.';
    }
@endphp
<div class="col-xl-12">
    <div class="card custom-card">
        <div class="card-header  justify-content-between">
            <div class="card-title">Affordability Summary </div>

        </div>
        <div class="card-body">
            <div class="d-flex w-100">
                <div class="d-flex align-items-center justify-content-between w-100 flex-wrap">
                    <div class="me-3">
                        <p class="fw-semibold fs-16 mb-0">{{ $loan_helper->formatCurrency($averageIncome) }}
                        </p>
                        <p class="text-muted mb-0">Average Monthly Income</p>
                    </div>
                    <div class="me-3">
                        <p class="fw-semibold fs-16 mb-0">{{ $loan_helper->formatCurrency($averageexpenses) }}
                        </p>
                        <p class="text-muted mb-0">Average Monthly Expenses</p>
                    </div>
                    <div class="me-3">
                        <p class="fw-semibold fs-16 mb-0">{{ $loan_helper->formatCurrency($surplus) }}
                        </p>
                        <p class="text-muted mb-0">Surplus</p>
                    </div>
                    <div class="me-3">
                        <p class="fw-semibold fs-16 mb-0">{{ $noOfLoans }}
                        </p>
                        <p class="text-muted mb-0">No. of Active Loans</p>
                    </div>
                    <div class="me-3">
                        <p class="fw-semibold fs-16 mb-0">{{ $loan_helper->formatCurrency($loanRepayments) }} (Monthly)
                        </p>
                        <p class="text-muted mb-0">Estimated Loam Repayment</p>
                    </div>
                    <div class="me-3">
                        <p class="fw-semibold fs-16 mb-0">
                            {{ isset($latestcreditScore->score_value) ? $latestcreditScore->score_value : 0 }}
                        </p>
                        <p class="text-muted mb-0">Credit Score</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="col-xl-12">
    <div class="card custom-card">
        <div class="card-header  justify-content-between">
            <div class="card-title">Summary </div>
            <div class="card-title">Score {{ $score }} </div>
        </div>
        <div class="card-body">
            @if ($error)
                <div class="alert alert-solid-danger alert-dismissible fade error_msg show text-dark">
                    {{ $approvemessage }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i
                            class="bi bi-x"></i></button>
                </div>
            @endif
            <table id="delete-datatable" class="table table-search- text-nowrap mt-4 mb-4 mb-4">
                <thead>
                    <tr>
                        <th class="fw-semibold text-dark mb-0">Metrics</th>
                        <th class="fw-semibold text-dark mb-0">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-semibold text-dark mb-0">Credit score:</td>
                        <td class="fw-semibold mb-0">{{ $credirScoremessage }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-dark mb-0">Surplus:</td>
                        <td class="fw-semiboldmb-0">{{ $surplusMessage }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-dark mb-0">No of Loans:</td>
                        <td class="fw-semibold  mb-0">{{ $loanMessage }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-dark mb-0">Salary:</td>
                        <td class="fw-semibold  mb-0 fs-18">{{ $salaryMessage }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-dark mb-0">Loans:</td>
                        <td class="fw-semibold mb-0">{{ $loanRepaymentsmsg }}</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold text-dark mb-0">Loan Amount recomendation:</td>
                        <td class="fw-semibold mb-0">{{ $loan_helper->formatCurrency($loanAmountrecom) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="card custom-card">
    <div class="card-body text-dark">
        <div class="row">
            <div class="deleted-table table-responsive col-md-6">
                <table id="delete-datatable" class="table table-search- text-nowrap mt-4 mb-4 mb-4">
                    <thead>
                        <tr>
                            <th colspan='3' class="fw-semibold text-dark mb-0">Income</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-light">
                            <td class="fw-semibold text-dark mb-0">Particular</td>
                            <td class="fw-semibold text-dark mb-0">Bank Statement Calulator</td>
                            <td class="fw-semibold text-dark mb-0">Frequency</td>
                        </tr>
                        @php
                            $i = 0;
                        @endphp
                        @foreach ($summary['income'] as $k => $v)
                            @if ($k == 'summary')
                            @else
                                <tr>
                                    <td>{{ $k }}</td>
                                    <td>{{ $loan_helper->formatCurrency($v['total']) }}</td>
                                    <td>{{ $v['frequency'] }}</td>
                                </tr>
                            @endif
                        @endforeach
                        <tr class="bg-secondary">
                            <td>Total</td>
                            <td colspan="2">{{ $loan_helper->formatCurrency($totalIncome) }}</td>

                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="deleted-table table-responsive col-md-6">
                <table id="delete-datatable" class="table table-search- text-nowrap mt-4 mb-4 mb-4">
                    <thead>
                        <tr>
                            <th colspan='3' class="fw-semibold text-dark mb-0">Expenses</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-light">
                            <td class="fw-semibold text-dark mb-0">Particular</td>
                            <td class="fw-semibold text-dark mb-0">Bank Statement Calulator</td>
                            <td class="fw-semibold text-dark mb-0">Frequency</td>
                        </tr>
                        @php
                            $i = 0;
                        @endphp
                        @foreach ($summary['expenses'] as $k => $v)
                            @if ($k == 'summary')
                            @else
                                @if ($v['total'] !== '0.00')
                                    <tr>
                                        <td>{{ $k }}</td>
                                        <td>{{ $loan_helper->formatCurrency($v['total']) }}</td>
                                        <td>{{ $v['frequency'] }}</td>
                                    </tr>
                                @endif
                            @endif
                        @endforeach
                        <tr class="bg-secondary">
                            <td>Total</td>
                            <td colspan="2">{{ $loan_helper->formatCurrency($totalexpenses) }}</td>

                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="deleted-table table-responsive">
                <table id="delete-datatable" class="table table-search- text-nowrap mt-4 mb-4 mb-4">
                    <thead>
                        <tr>
                            <th colspan='3' class="fw-semibold text-dark mb-0">Personal Loans</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-light">
                            <td class="fw-semibold text-dark mb-0">Creditor</td>
                            <td class="fw-semibold text-dark mb-0">Creditor Type</td>
                            <td class="fw-semibold text-dark mb-0">Date Range</td>
                            <td class="fw-semibold text-dark mb-0">Frequency</td>
                            <td class="fw-semibold text-dark mb-0">Repayment</td>
                            <td class="fw-semibold text-dark mb-0">Total Paid</td>
                            <td class="fw-semibold text-dark mb-0">Next Pay date</td>
                        </tr>
                        @php
                            $i = 0;
                        @endphp
                        @foreach ($loans as $k => $v)
                            @if ($v->analysis->amount->total !== '0.00')
                                @foreach ($v->subgroup as $sub)
                                    <tr>
                                        <td>{{ $sub->name }}</td>
                                        <td>{{ $k }}</td>
                                        <td>{{ $loan_helper->formateDate($sub->analysis->range->startDate) }} to
                                            {{ $loan_helper->formateDate($sub->analysis->range->endDate) }}</td>
                                        <td>{{ $sub->analysis->frequency->display }}</td>
                                        <td>{{ $loan_helper->formatCurrency($sub->analysis->frequency->amount) }}</td>
                                        <td>{{ $loan_helper->formatCurrency($sub->analysis->amount->total) }}</td>
                                        <td>{{ $loan_helper->formateDate($sub->analysis->frequency->next->date) }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>


    </div>
</div>
