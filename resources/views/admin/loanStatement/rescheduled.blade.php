<tr class="bg-light">
    <td colspan="10" class="fw-bold fs-16"> Reschedule payments</td>
</tr>
<tr>
    <th>#</th>
    <th>Pay Date</th>
    <th>Settlement Date</th>
    <th>Amount</th>
    <th>Interest & fee</th>
    <th>Reschedule Fee</th>
    <th>Dishonoured Fee</th>
    <th>Principal Payment</th>
    <th>Payment Status</th>
    <th>Action</th>
</tr>
@foreach ($reschedule as $ls)
    <tr>

        @if ($ls['frequency'] == 'fortnightly')
            <td> {{ $i }} weeks </td>
            @php $i += 2; @endphp
        @else
            <td> {{ $i++ }} week </td>
        @endif

        <td>{{ $ls['payment_date'] }}</td>
        <td>{{ $ls['settlement_date'] }}</td>
        <td>{{ $ls['weekly_payment'] }}</td>
        <td>{{ $ls['interest'] }}</td>
        <td>{{ $ls['reschedule_fee'] }}</td>
        <td>{{ $ls['late_fee'] }}</td>
        <td>{{ $ls['principal_payment'] }}</td>
        <td>{{ $ls['payment_status'] }}</td>
        <td>
            <div class="btn-group">
                <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Action
                </button>
                <ul class="dropdown-menu">
                    @if ($ls['payment_status'] !== 'WaitingOnClearedFunds' && $ls['payment_status'] !== 'Complete')
                        <li><a class="dropdown-item" href="{{ route('loan.payment', $ls->id) }}">Process
                                Payment</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal"
                                data-bs-target="#repayment-{{ $ls->id }}">Re-schedule
                                Payment</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal"
                                data-bs-target="#update-{{ $ls->id }}">Update
                                Statement</a></li>
                    @endif

                    <hr class="dropdown-divider">
                    </li>

                    <li><a class="dropdown-item" href="javascript:void(0);"
                            onclick="toggleTable('transaction_{{ $ls->id }}')">View
                            Transaction</a></li>
                    <li>
                </ul>
            </div>
        </td>
    </tr>
    @if (count($ls->transaction) > 0)
        <tr id="transaction_{{ $ls->id }}" class="transactions_details" style="display:none">
            <td colspan="10">
                @include('admin.loanStatement.transaction')
            </td>
        </tr>
    @endif
    @include('admin.loanStatement.repayment')
@endforeach
