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
                            <th class="text-muted">Intrest</th>
                            <th class="text-muted">Establishment Fee</th>
                            <th class="text-muted">Principal Payment</th>
                            <th class="text-muted">Reschedule Fee</th>
                            <th class="text-muted">Dishourned Fee</th>
                            <th class="text-muted">Total</th>


                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($report['report'] as $k => $v)
                        @if($v['weekly_payment'] > 0)
                            <tr class="bg-light">
                                <td colspan="8" class="fw-bold">{{ $k }}</td>
                            </tr>
                            @foreach ($v['transactions'] as $t)
                            @if($t->payment_status == 'Complete')
                             <tr class="bg-success">
                            @elseif($t->payment_status == 'Dishonoured')
                             <tr class="bg-danger">
                            @elseif($t->payment_status == 'WaitingOnClearedFunds')
                             <tr class="bg-warning">
                            @elseif($t->payment_status == 'Re-scheduled')
                             <tr class="bg-secondary">
                            @else
                             <tr>
                            @endif
                               
                                    <td>
                                        @if ($t->loan_application_id)
                                            <u>#<a target="_blank"
                                                    href="{{ route('loan.view', $t->loan_application_id) }}">{{ $t->loan_application_id }}</a></u>
                                                      @endif
                                    </td>
                                    <td><b>{{$t->payment_status }}</b> - {{ $loan_helper->getUserNameByAppID($t->loan_application_id) }}
                                        ({{ $t->id }})</td>
                                    <td>{{ $loan_helper->formatCurrency($t->weekly_interest) }}</td>
                                    <td>{{ $loan_helper->formatCurrency($t->weekly_establishment_fee) }}</td>
                                    <td>{{ $loan_helper->formatCurrency($t->principal_payment) }}</td>
                                    <td>{{ $loan_helper->formatCurrency($t->reschedule_fee) }}</td>
                                    <td>{{ $loan_helper->formatCurrency($t->late_fee) }}</td>
                                    <td>{{ $loan_helper->formatCurrency($t->weekly_payment) }}</td>
                          </tr>
                        @endforeach
                        <tr class="bg-primary">
                            <td  class="fw-bold ">Total</td>
                            <td  class="fw-bold ">Collection {{ $loan_helper->roundNumber($v['completePayment'] / $v['weekly_payment'] * 100)}} %</td>
                            <td colspan="">
                                {{ $loan_helper->formatCurrency($v['weekly_interest']) }}
                            </td>
                            <td class="text-nowrap">

                                {{ $loan_helper->formatCurrency($v['weekly_establishment_fee']) }}
                            </td>
                            <td class="text-nowrap">

                                {{ $loan_helper->formatCurrency($v['principal_payment']) }}
                            </td>
                            <td>

                                {{ $loan_helper->formatCurrency($v['reschedule_fee']) }}
                            </td>
                            <td>

                                {{ $loan_helper->formatCurrency($v['dishonored_fee']) }}
                            </td>
                            <td>

                                {{ $loan_helper->formatCurrency($v['weekly_payment']) }}
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
