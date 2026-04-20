<div class="deleted-table table-responsive">
    <table id="delete-datatable" class="table table-search- text-nowrap mt-4 mb-4 mb-4">
        <thead>
            <tr>
                <th colspan='3' class="fw-semibold text-dark mb-0">Loans</th>
            </tr>
        </thead>
        <tbody>
            <tr class="bg-light">
                <td class="fw-semibold text-dark mb-0">Creditor</td>
                <td class="fw-semibold text-dark mb-0">Start Date</td>
                <td class="fw-semibold text-dark mb-0">End Date</td>
                <td class="fw-semibold text-dark mb-0">Amount Funded</td>
                <td class="fw-semibold text-dark mb-0">Frequency</td>
                <td class="fw-semibold text-dark mb-0">Average Debit Amount</td>
                <td class="fw-semibold text-dark mb-0">On Going</td>
                <td class="fw-semibold text-dark mb-0">Ongoing Repayment Amount</td>
            </tr>
            @php
                $i = 0;
            @endphp
            @foreach ($loans as $k => $v)
                <tr>
                    <td colspan="8" class="bg-primary">{{ $loan_helper->beautifyVariableName($k) }}</td>
                </tr>
                @foreach ($v as $y)
                    @if (isset($y->analysisCategory->transactionGroups))
                        @foreach ($y->analysisCategory->transactionGroups as $tg)
                            <tr>
                                <td>{{ $tg->name }}</td>
                                @foreach ($tg->analysisPoints as $t)
                                    @if ($t->name == 'amountFunded')
                                        <td>{{ $loan_helper->formatCurrency($t->value) }}</td>
                                    @elseif ($t->name == 'startDate')
                                        <td>{{ $t->value }}</td>
                                    @elseif ($t->name == 'endDate')
                                        <td>{{ $t->value }}</td>
                                    @elseif ($t->name == 'frequencyDay')
                                        <td>{{ $t->value }}</td>
                                    @elseif ($t->name == 'averageDebitAmount')
                                        <td>{{ $loan_helper->formatCurrency($t->value) }}</td>
                                    @elseif ($t->name == 'loanOngoing')
                                        <td>{{ $t->value }}</td>
                                    @elseif ($t->name == 'ongoingRepaymentAmount')
                                        <td>{{ $loan_helper->formatCurrency($t->value) }}</td>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>
