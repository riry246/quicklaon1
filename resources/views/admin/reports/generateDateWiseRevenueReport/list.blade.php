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
                            <th class="text-muted">Amount</th>
                            <th class="text-muted">Status</th>
                            <th class="text-muted" colspan="2">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($report['report'] as $k => $v)
                            @if ($v['totalNumberOfTransactions'] > 0)
                                <tr class="bg-light">
                                    <td colspan="9" class="fw-bold">{{ $k }}</td>
                                </tr>
                                @foreach ($v['transactions'] as $t)
                                    @if ($t['transaction']->status == 'Complete')
                                        <tr class="bg-success">
                                        @elseif($t['transaction']->status == 'Dishonoured')
                                        <tr class="bg-danger">
                                        @elseif($t['transaction']->status == 'WaitingOnClearedFunds')
                                        <tr class="bg-warning">
                                        @elseif($t['transaction']->status == 'Re-scheduled')
                                        <tr class="bg-secondary">
                                        @else
                                        <tr>
                                    @endif
                                    @if ($t['transaction']->application_id)
                                        <td>

                                            <u>#<a target="_blank"
                                                    href="{{ route('loan.view', $t['transaction']->application_id) }}">{{ $t['transaction']->application_id }}</a></u>
                                        </td>
                                    @endif
                                    </td>
                                    <td>{{ $loan_helper->getUserName($t['transaction']->user_id) }}</td>
                                    <td>{{ $t['transaction']->transaction_id }}</td>
                                    <td>{{ $t['transaction']->loan_statements_id }}</td>
                                    <td>{{ $loan_helper->formatCurrency($t['transaction']->amount) }}</td>
                                    <td>{{ $t['transaction']->status }}</td>
                                    <td colspan="3"><u>#<a target="_blank"
                                                href="{{ route('transaction.view', $t['transaction']->id) }}">{{ $t['transaction']->status_description }}</a>
                                    </td>
                                    </tr>
                                @endforeach
                                <tr class="bg-primary">
                                    <td colspan="" class="fw-bold ">Total Collection</td>
                                    <td>
                                        <span class="fs-12 text-muted">Revenue</span></br>
                                        {{ $loan_helper->formatCurrency($v['totalRevenue']) }}
                                         <br/>Collection :{{ $loan_helper->roundNumber($v['totalpercentage']) }}%
                                    </td>
                                    <td>
                                        <span class="fs-12 text-muted">Transaction</span></br>
                                        {{ $v['totalNumberOfTransactions'] }} Loans
                                    </td>
                                    <td>
                                        <span class="fs-12 text-muted">Principal</span></br>
                                        {{ $loan_helper->formatCurrency($v['totalPrincipalPayment']) }}
                                    </td>
                                    <td>
                                        <span class="fs-12 text-muted">Interest</span></br>
                                        {{ $loan_helper->formatCurrency($v['totalWeeklyInterest']) }}
                                    </td>
                                    <td>
                                        <span class="fs-12 text-muted">Establishment</span></br>
                                        {{ $loan_helper->formatCurrency($v['totalWeeklyEstablishmentFee']) }}
                                    </td>
                                    <td><span class="fs-12 text-muted">Dishonored</span></br>
                                        {{ $loan_helper->formatCurrency($v['totalLateFee']) }} </td>
                                    <td><span class="fs-12 text-muted">Reschedule</span></br>
                                        {{ $loan_helper->formatCurrency($v['totalRescheduleeFee']) }} </td>
                                    <td><span class="fs-12 text-muted">Overdue Intreset</span></br>
                                        {{ $loan_helper->formatCurrency($v['totalOverdueInterest']) }} </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>
