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
                            <th class="text-muted">Statement ID</th>
                            <th class="text-muted">Dishourned Amount</th>
                            <th class="text-muted">Transaction Id</th>

                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($report['report'] as $k => $v)
                            @if ($v['count'] > 0)
                                <tr class="bg-light">
                                    <td colspan="8" class="fw-bold">{{ $k }}</td>
                                </tr>
                                @foreach ($v['loan_data'] as $t)
                                    <tr>
                                        <td>
                                            <u>#<a target="_blank"
                                                    href="{{ route('loan.view', $t['loan']->id) }}">{{ $t['loan']->id }}</a></u>
                                        </td>
                                        <td>{{ $loan_helper->getUserName($t['loan']->user_id) }}</td>
                                        @php
                                            $statement_id = null;
                                            $dishourned_amount = 0;
                                        @endphp

                                        @foreach ($t['dishonored_statements'] as $s)
                                            @php
                                                $statement_id .= $s->id . ',';
                                                $dishourned_amount += $s->weekly_payment;
                                            @endphp
                                        @endforeach
                                        <td>
                                            {{ rtrim($statement_id, ',') }}
                                        </td>
                                        <td>
                                            {{ $loan_helper->formatCurrency($dishourned_amount) }}
                                        </td>
                                        <td>
                                        @if($s->transaction_id)
                                            <a target="_blank"
                                                href="{{ route('transaction.view', $s->transaction_id) }}">#<u>{{ $s->transaction_id }}</u></a>
                                        @else
                                        N/A
                                        
                                        @endif
                                        </td>
                                        
                                    </tr>
                                   
                                @endforeach
                                 <tr class="bg-primary">
                                    <td colspan="2" class="fw-bold">Total</td>
                                    <td>{{ $v['count'] }} Statements</td>
                                    <td colspan="2">{{ $loan_helper->formatCurrency($v['amount']) }}</td>
                                    
                                </tr>
                            @endif
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>
