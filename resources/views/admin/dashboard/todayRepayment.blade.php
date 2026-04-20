<div class="row ">
    <div class="col-xl-12">
        <div class="card custom-card bg-light">
            <div class="card-header justify-content-between">
                <div class="card-title">
                    @php
                        $formattedDate = date('Y-m-d', strtotime($date));
                        $today = date('Y-m-d');
                        $tomorrow = date('Y-m-d', strtotime('+1 day', strtotime($today)));
                        $dayOfWeek = date('l', strtotime($date));
                    @endphp

                    @if ($formattedDate == $today)
                        Today's
                    @elseif ($formattedDate == $tomorrow)
                        Tomorrow's
                    @else
                        {{ $dayOfWeek }}'s
                    @endif

                    Payments
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <div>
                        <h3 class="fs-15">
                            Total:{{ $loan_helper->formatCurrency($dashboard_helper->getRepaymentsByDateTotal($date)) }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="delete-datatable" class="table  table-search mb-4 mt-4">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Applicant Name</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Payment Status</th>
                                <th scope="col">Dishourned Possibility</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($dashboard_helper->getRepaymentsByDate($date) as $p)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td><a target="_blank"
                                            href="{{ route('loan.view', $p->loan_application_id) }}"><u>{{ $loan_helper->getUserName($p->loanApplication->user_id) }}</u></a>
                                    </td>
                                    @php
                                        // Generate a random session code
                                        $randomSessionCode = bin2hex(random_bytes(16)); // generates a 32-character random string
                                    @endphp
                                    <td><a target="_blank" href="{{ route('loan.statement', ['userid' => $randomSessionCode, 'id' => $p->loan_application_id]) }}">$ {{ $p->weekly_payment }}</a></td>
                                    <td>{{ $p->payment_status }}</td>
                                    <td>{{ $p->dishourned_possibility ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
