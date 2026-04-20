<div class="col-xl-12">
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">{{ $report_type->name }} ({{ $loan_helper->formateDate($date['dateForm']) }} -
                {{ $loan_helper->formateDate($date['dateTo']) }})</div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-search mb-4 mt-4" aria-describedby="file-export_info">
                    <thead>
                        <tr>
                            <th class="text-muted">Application Id</th>
                            <th class="text-muted">Customer Name</th>
                            <th class="text-muted">Transaction ID</th>
                            <th class="text-muted">Statement ID</th>
                            <th class="text-muted">Credit</th>
                            <th class="text-muted">Debit</th>
                            <th class="text-muted">Status</th>
                            <th class="text-muted" colspan="2">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($report['report'] as $k => $v)
                            @if ($v['totalNumberOfTransactions'] > 0)
                                <tr class="bg-light">
                                    <td colspan="8" class="fw-bold">{{ $k }}</td>
                                </tr>
                                @foreach ($v['transactions'] as $t)
                                    <tr>
                                        <td>
                                            @if ($t->application_id)
                                                <u>#<a target="_blank"
                                                        href="{{ route('loan.view', $t->application_id) }}">{{ $t->application_id }}</a></u>
                                        </td>
                                @endif
                                <td>{{ $loan_helper->getUserName($t->user_id) }}</td>
                                <td>{{ $t->transaction_id }}</td>
                                <td>{{ $t->loan_statements_id }}</td>
                                <td>
                                    @if ($t->type == 'Credit')
                                        <span class="text-success">+
                                            {{ $loan_helper->formatCurrency($t->amount) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($t->type == 'Debit')
                                        <span class="text-danger">-
                                            {{ $loan_helper->formatCurrency($t->amount) }}</span>
                                    @endif
                                </td>
                                <td>{{ $t->status }}</td>
                                <td colspan="2"><u>#<a target="_blank"
                                            href="{{ route('transaction.view', $t->id) }}">{{ $t->status_description }}</a>
                                </td>
                                </tr>
                            @endforeach
                            <tr class="bg-primary">
                                <td colspan="" class="fw-bold ">Total</td>
                                <td colspan="3">
                                    <span class="fs-12 text-muted">Transaction</span></br>
                                    {{ $v['totalNumberOfTransactions'] }} Transactions
                                </td>
                                <td class="text-nowrap">
                                    <span class="fs-12 text-muted">Credit</span></br>
                                    {{ $loan_helper->formatCurrency($v['totalCredit']) }}
                                </td>
                                <td class="text-nowrap">
                                    <span class="fs-12 text-muted">Debit</span></br>
                                    {{ $loan_helper->formatCurrency($v['totalDebit']) }}
                                </td>
                                <td colspan="2">
                                    <span class="fs-12 text-muted">Grand Total</span></br>
                                    {{ $loan_helper->formatCurrency($v['total']) }}
                                </td>
                            </tr>
                        @endif
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>
