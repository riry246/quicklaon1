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
                            <th class="text-muted">#</th>
                            <th class="text-muted">Application Id</th>
                            <th class="text-muted">Customer Name</th>
                            <th class="text-muted">Amount</th>
                            <th class="text-muted">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($report['report'] as $k => $v)
                            @if ($v['total_number_of_loans'] > 0)
                                <tr class="bg-light">
                                    <td rowspan="{{ $v['total_number_of_loans'] + 2 }}">{{ $i++ }}</td>
                                    <td colspan="4" class="fw-bold">{{ $k }}</td>
                                </tr>

                                @foreach ($v['loans'] as $l)
                                    <tr>
                                        <td><u>#<a target="_blank"
                                                    href="{{ route('loan.view', $l->id) }}">{{ $l->id }}</a></u>
                                        </td>
                                        <td>{{ $loan_helper->getUserName($l->user_id) }}</td>
                                        <td>{{ $loan_helper->formatCurrency($l->approved_amount) }}</td>
                                        <td>{{ $l->status }}</td>
                                    </tr>
                                @endforeach
                                <tr class="bg-primary">
                                    <td colspan="2" class="fw-bold">Total</td>
                                    <td>{{ $loan_helper->formatCurrency($v['total_loan_value']) }}</td>
                                    <td>{{ $v['total_number_of_loans'] }} Loans</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>
