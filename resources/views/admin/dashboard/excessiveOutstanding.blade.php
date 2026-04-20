@php
    $applications = $dashboard_helper->getExcessiveOutstandingAccount();
@endphp

<div class="col-xl-12">
    <div class="card custom-card mb-0">
        <div class="card-header justify-content-between">
            <div class="card-title">
                Excessive Outstanding Applications
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="card custom-card bg-light">
                        <div class="card-header justify-content-between">
                            <div class="card-title">{{ count($applications) }} Excessive Outstanding Applications</div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-search mb-4 mt-4">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Loan Application ID</th>
                                            <th>Customer Name</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($applications as $application)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    @if ($application->id)
                                                        <u>#<a target="_blank"
                                                                href="{{ route('loan.view', $application->id) }}">{{ $application->id }}</a></u>
                                                    @endif
                                                </td>
                                                <td>{{ $loan_helper->getUserNameByAppID($application->id) }}</td>
                                                <td>{{ $loan_helper->formatCurrency($application->approved_amount) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
