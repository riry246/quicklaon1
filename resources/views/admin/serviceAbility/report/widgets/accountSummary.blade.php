<div class="card custom-card">
    <div class="card-header">
        <div class="card-title">
            <i class="bi bi-bank mx-2"></i>Account Summary
        </div>
    </div>
    <div class="card-body text-dark">
        <div class="deleted-table table-responsive">
            <table id="delete-datatable" class="table table-search- text-nowrap mt-2 mb-2">
                <thead>
                    <tr>
                        <th>Account Name</th>
                        <th>Type</th>
                        <th>Account holder</th>
                        <th>Ownership</th>
                        <th>Available</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($statements->data->accounts as $s)
                        <tr class="text-dark">
                            <td><a target="_blank"
                                    href="{{ route('bankStatement.statement', ['id' => $basiq_user_id, 'accountID' => $s->id]) }}">
                                    <u>{{ $s->name }}</u>
                                </a>
                            </td>
                            <td>{{ $s->class->type }}</td>
                            <td>{{ $s->accountHolder }}</td>
                            <td>{{ $s->accountOwnership }}</td>
                            <td> {{ $loan_helper->formatCurrency($s->availableFunds) }}</td>
                            <td> {{ $loan_helper->formatCurrency($s->balance) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
