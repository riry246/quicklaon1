<div class="col-xl-12">
    <div class="card custom-card">
        <div class="card-header justify-content-between border-bottom-1">
            <div class="card-title">
                Loan Statement
            </div>
            <a href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="true"
                aria-controls="collapseExample" class="">
                <i class="ri-arrow-down-s-line fs-18 collapse-open"></i>
                <i class="ri-arrow-up-s-line collapse-close fs-18"></i>
            </a>
        </div>
        <div class="collapse show" id="collapseExample" style="">
            <div class="card-body">
                <table class="table table-search text-nowrap mt-4 mb-4 mb-4">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Settlement Date</th>
                            <th>Amount</th>
                            <th>Interest/ Fees</th>
                            <th>Principle</th>
                            <th>Late Fee</th>
                            <th>Reschedule Fee</th>
                            <th>Payment Frequency</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaction->statements as $s)
                            <tr>
                                <td>{{ $s->id }}</td>
                                <td> {{ $s->settlement_date }}</td>
                                <td>$ {{ $s->weekly_payment }}</td>
                                <td>$ {{ $s->interest }}</td>
                                <td>$ {{ $s->principal_payment }}</td>
                                <td>$ {{ $s->late_fee }}</td>
                                <td>$ {{ $s->reschedule_fee }}</td>
                                <td> {{ $s->frequency }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
