<div class="row">
    <div class="col-xxl-12 col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">
                    Loan Statement
                </div>
                @php
                    $frequency = 1;
                @endphp
                @foreach ($loanstatement as $ls)
                    @php
                        $frequency = $ls['frequency'];
                    @endphp
                @endforeach
                <button class="btn btn-sm btn-success">{{ ucfirst($frequency) }}</button>
                @if($user->basiq_user_id)
                <a href="{{ route('loan.statement', array('userid'=>$user->basiq_user_id , 'id'=>$loanapplication->id)) }}" class="btn btn-sm btn-primary">View Detail</a>
           @endif
            </div>
            <div class="card-body">
                <div class="deleted-table table-responsive">
                    <table class="table table-search- text-nowrap mt-4 mb-4 mb-4">
                        <thead>
                            <tr>
                                <th>Week</th>
                                <th>Pay Date</th>
                                <th>Settlement Date</th>
                                <th>Opening Balance</th>
                                <th>Weekly Payment</th>
                                <th>Interest & fee</th>
                                <th>Principal Payment</th>
                                <th>Closing Balance</th>
                                <th>Payment Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                if ($loanapplication['frequency'] == 'fortnightly') {
                                    $i = 2;
                                } else {
                                    $i = 1;
                                }
                            @endphp
                            @foreach ($loanstatement as $ls)
                                <tr>
                                    @if ($ls['frequency'] == 'fortnightly')
                                        <td> {{ $i }} weeks </td>
                                        @php $i += 2; @endphp
                                    @else
                                        <td> {{ $i++ }} week </td>
                                    @endif
                                    <td>{{ $loan_helper->formateDate($ls['payment_date'] )}}</td>
                                    <td>{{ $loan_helper->formateDate($ls['settlement_date'])}}</td>
                                    <td>$ {{ $ls['opening_balance'] }}</td>
                                    <td>$ {{ $ls['weekly_payment'] }}</td>
                                    <td>$ {{ $ls['interest'] }}</td>
                                    <td>$ {{ $ls['principal_payment'] }}</td>
                                    <td>$ {{ $ls['closing_balance'] }}</td>
                                    <td>{{ $ls['payment_status'] }}</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
