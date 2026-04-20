@inject('loan_helper', 'App\Http\Helpers\LoanHelper')
<div class="deleted-table table-responsive">
    Date range : {{ $loan_helper->formateDate($datefrom) }} to {{ $loan_helper->formateDate($dateto) }}
    <table id="delte-datatable" class="table table-search text-nowrap mt-4 mb-4 mb-4 ">
        <thead>
            <tr>
                <th>Settlement Date</th>
                <th>Application ID</th>
                <th>Outstanding Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($badDebt as $k => $c)
                <tr>
                    <td colspan="3"><a href="{{ route('customer.view', $k) }}" class="fw-bold mb-0">{{ $loan_helper->getUserName($k) }}</a>
                    </td>
                </tr>
                @foreach ($c as $l => $p)
                    @foreach ($p as $o)
                        <tr>
                            <td>{{ $loan_helper->formateDate($l) }}</td>
                            <td><a href="{{ route('loan.view', $o['loan_id']) }}">#{{ $o['loan_id'] }}</a></td>
                            <td>$ {{ $o['outstanding_amount'] }}
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            @empty
                <tr>
                    <td colspan="3">No Bad Debts / Write Offs Record</td>
                </tr>
            @endforelse

        </tbody>
    </table>
</div>
