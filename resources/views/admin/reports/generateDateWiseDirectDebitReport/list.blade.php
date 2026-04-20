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
                            <th class="text-muted">Description</th>
                            <th class="text-muted">Transaction ID</th>
                            <th class="text-muted">BSB</th>
                            <th class="text-muted">Account No</th>
                            <th class="text-muted">Amount</th>
                            <th class="text-muted">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($report['report'] as $k => $v)
                            @if ($v['total']['count'] > 0)
                                <tr class="bg-light">
                                    <td colspan="8" class="fw-bold">{{ $k }}</td>
                                </tr>
                                @foreach ($v['transactions'] as $t)
                                    @if ($t->status == 'Complete')
                                        <tr class="bg-success">
                                        @elseif($t->status == 'Dishonoured')
                                        <tr class="bg-danger">
                                        @elseif($t->status == 'WaitingOnClearedFunds')
                                        <tr class="bg-warning">
                                        @elseif($t->status == 'Re-scheduled')
                                        <tr class="bg-secondary">
                                        @else
                                        <tr>
                                    @endif
                                    @if ($t->application_id)
                                        <td>
                                            <u>#<a target="_blank"
                                                    href="{{ route('loan.view', $t->application_id) }}">{{ $t->application_id }}</a></u>
                                        </td>
                                    @endif
                                    </td>
                                    <td>{{ $loan_helper->getUserName($t->user_id) }}</td>
                                    <td><u>#<a target="_blank"
                                                href="{{ route('transaction.view', $t->id) }}">{{ $t->status_description }}</a>
                                    </td>
                                    <td>{{ $t->transaction_id }}</td>
                                    <td>{{ substr_replace(substr($t->bsb, 0, 6), '-', 3, 0) }}</td>
                                    <td>{{ substr($t->bsb, 6) }}</td>
                                    <td>{{ $loan_helper->formatCurrency($t->amount) }}</td>
                                    <td>{{ $t->status }}</td>

                                    </tr>
                                @endforeach
                                <tr class="bg-primary">
                                    <td>
                                        <span class="fs-12 text-bold">Total Transactions</span></br>
                                        {{ $v['total']['count'] }}
                                    </td>
                                    <td>
                                        <span class="fs-12 text-muted">Successful </span></br>
                                        {{ $loan_helper->formatCurrency($v['total']['success']) }}
                                    </td>
                                    <td>
                                        <span class="fs-12 text-muted">Dishonored</span></br>
                                        {{ $loan_helper->formatCurrency($v['total']['dishonoured']) }}
                                    </td>
                                    <td><span class="fs-12 text-muted">Pending</span></br>
                                        {{ $loan_helper->formatCurrency($v['total']['pending']) }} </td>
                                    <td>
                                        <span class="fs-12 text-muted">Success Rate</span></br>
                                        {{ $loan_helper->roundNumber($v['total']['percentage']) }}%
                                    </td>
                                    <td>
                                        <span class="fs-12 text-muted">Total</span></br>
                                        {{ $loan_helper->formatCurrency($v['total']['total']) }}
                                    </td>


                                    <td colspan="2"><span class="fs-12 text-muted">Fee amount including
                                            GST</span></br>
                                        {{ $loan_helper->formatCurrency($v['total']['transactionCharge']) }} </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>
