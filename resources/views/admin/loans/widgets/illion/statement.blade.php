@php
    $statement = json_decode($tab->statement);
@endphp

<div class="card custom-card">
    <div class="card-body text-dark bg-light">
        <div class="d-flex w-100">
            <div class="d-flex align-items-center justify-content-between w-100 flex-wrap">
                <div class="me-3">
                    <p class="fw-semibold fs-13 mb-1">Date Range
                    </p>
                    <p class="text-muted mb-3">{{ $statement->startDate }} to {{ $statement->endDate }}</p>
                </div>
            </div>
        </div>
        <div class="d-flex w-100">
            <div class="d-flex align-items-center justify-content-between w-100 flex-wrap">
                <div class="me-3">
                    <p class="fw-semibold fs-13 mb-1">Total Credits
                    </p>
                    <p class="text-muted mb-3">{{ $loan_helper->formatCurrency($statement->totalCredits) }}</p>
                </div>
                <div class="me-3">
                    <p class="fw-semibold fs-13 mb-1">Total Debits
                    </p>
                    <p class="text-muted mb-3">{{ $loan_helper->formatCurrency($statement->totalDebits) }}</p>
                </div>
                <div class="me-3">
                    <p class="fw-semibold fs-13 mb-1">Opening Balance
                    </p>
                    <p class="text-muted mb-3">{{ $loan_helper->formatCurrency($statement->openingBalance) }}</p>
                </div>
                <div class="me-3">
                    <p class="fw-semibold fs-13 mb-1">Closing Balance
                    </p>
                    <p class="text-muted mb-3">{{ $loan_helper->formatCurrency($statement->closingBalance) }}</p>
                </div>
                <div class="me-3">
                    <p class="fw-semibold fs-13 mb-1">Min Balance
                    </p>
                    <p class="text-muted mb-3">{{ $loan_helper->formatCurrency($statement->minBalance) }}</p>
                </div>
                <div class="me-3">
                    <p class="fw-semibold fs-13 mb-1">Max Balance
                    </p>
                    <p class="text-muted mb-3">{{ $loan_helper->formatCurrency($statement->maxBalance) }}</p>
                </div>
                <div class="me-3">
                    <p class="fw-semibold fs-13 mb-1">Days In Negative
                    </p>
                    <p class="text-muted mb-3">{{ $statement->daysInNegative }}</p>
                </div>
            </div>
        </div>
        <div class="col-xxl-12 col-xl-12 mt-2">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        Statement
                    </div>
                </div>
                <div class="card-body">
                    <div class="deleted-table table-responsive">
                        <div class="text-center">

                        </div>
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
                                @foreach ($statement->details as $key => $statementDetail)
                                <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $statementDetail->date }}</td>
                                <td>{{ $statementDetail->text }}</td>
                                <td>@if($statementDetail->type == 'Debit')<span class="text-danger">- {{ $loan_helper->formatCurrency($statementDetail->amount) }}</span> @endif</td>
                                <td>@if($statementDetail->type == 'Credit')<span class="text-success"> {{ $loan_helper->formatCurrency($statementDetail->amount) }}</span> @endif</td>
                                <td><span class="text-secondary">{{ $loan_helper->formatCurrency($statementDetail->balance) }}</span></td>
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
