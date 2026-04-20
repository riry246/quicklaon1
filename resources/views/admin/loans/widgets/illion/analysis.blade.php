<div class="col-xxl-12 col-xl-12 mt-2">
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">
                Statement Analysis
            </div>
        </div>
        <div class="card-body">
            <div class="deleted-table table-responsive">
                <table id="delete-datatable" class="table table-search text-nowrap mt-4 mb-4">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Post Date</th>
                            <th>Description</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Balance</th>

                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($transactions as $key => $transactions)
                            @php
                                foreach ($transactions->tags as $tag) {
                                    if (isset($tag->creditDebit)) {
                                        $type = $tag->creditDebit;
                                    }
                                }
                            @endphp
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $transactions->date }}</td>
                                <td>{{ $transactions->description }}{{ $transactions->type ? ' - ( ' . $transactions->type . ' )' : '' }}
                                </td>
                                <td>
                                    @if ($type == 'debit')
                                        <span class="text-danger">
                                            {{ $loan_helper->formatCurrency($transactions->amount) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($type == 'credit')
                                        <span class="text-success">
                                            {{ $loan_helper->formatCurrency($transactions->amount) }}</span>
                                    @endif
                                </td>
                                <td><span
                                        class="text-secondary">{{ $loan_helper->formatCurrency($transactions->balance) }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
