@if ($tab->transactions)
    @php
        $transactions = json_decode($tab->transactions);
        $statementSummary = json_decode($tab->statementSummary);
        $statementAnalysis = json_decode($tab->statementAnalysis);

    @endphp

    <div class="card custom-card">
        <div class="card-body text-dark bg-light">
            <div class="d-flex w-100">
                <div class="d-flex align-items-center justify-content-between w-100 flex-wrap">
                    <div class="me-3">
                        <p class="fw-semibold fs-13 mb-1">Date Range
                        </p>
                        <p class="text-muted mb-3">{{ $statementSummary->transactionsStartDate ?? 'N/A' }} to
                            {{ $statementSummary->transactionsEndDate ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
            <div class="d-flex w-100">
                <div class="d-flex align-items-center justify-content-between w-100 flex-wrap">
                    <div class="me-3">
                        <p class="fw-semibold fs-13 mb-1">Total Credits
                        </p>
                        <p class="text-muted mb-3">{{ $loan_helper->formatCurrency($statementSummary->totalCredits) }}
                        </p>
                    </div>
                    <div class="me-3">
                        <p class="fw-semibold fs-13 mb-1">Total Debits
                        </p>
                        <p class="text-muted mb-3">{{ $loan_helper->formatCurrency($statementSummary->totalDebits) }}
                        </p>
                    </div>
                    <div class="me-3">
                        <p class="fw-semibold fs-13 mb-1">Opening Balance
                        </p>
                        <p class="text-muted mb-3">{{ $loan_helper->formatCurrency($statementSummary->openingBalance) }}
                        </p>
                    </div>
                    <div class="me-3">
                        <p class="fw-semibold fs-13 mb-1">Closing Balance
                        </p>
                        <p class="text-muted mb-3">{{ $loan_helper->formatCurrency($statementSummary->closingBalance) }}
                        </p>
                    </div>
                    <div class="me-3">
                        <p class="fw-semibold fs-13 mb-1">Min Balance
                        </p>
                        <p class="text-muted mb-3">{{ $loan_helper->formatCurrency($statementSummary->minBalance) }}</p>
                    </div>

                    <div class="me-3">
                        <p class="fw-semibold fs-13 mb-1">Credits Categorised
                        </p>
                        <p class="text-muted mb-3">
                            {{ $loan_helper->formatCurrency($statementSummary->creditsCategorised) }}</p>
                    </div>
                    <div class="me-3">
                        <p class="fw-semibold fs-13 mb-1">Credits Uncategorised
                        </p>
                        <p class="text-muted mb-3">
                            {{ $loan_helper->formatCurrency($statementSummary->creditsUncategorised) }}</p>
                    </div>
                    <div class="me-3">
                        <p class="fw-semibold fs-13 mb-1">Debits Categorised
                        </p>
                        <p class="text-muted mb-3">
                            {{ $loan_helper->formatCurrency($statementSummary->debitsCategorised) }}
                        </p>
                    </div>
                    <div class="me-3">
                        <p class="fw-semibold fs-13 mb-1">Debits Uncategorised
                        </p>
                        <p class="text-muted mb-3">
                            {{ $loan_helper->formatCurrency($statementSummary->debitsUncategorised) }}</p>
                    </div>
                    <div class="me-3">
                        <p class="fw-semibold fs-13 mb-1">Internal Transfer Debits
                        </p>
                        <p class="text-muted mb-3">
                            {{ $loan_helper->formatCurrency($statementSummary->internalTransferDebits) }}</p>
                    </div>
                    <div class="me-3">
                        <p class="fw-semibold fs-13 mb-1">Internal Transfer Credits
                        </p>
                        <p class="text-muted mb-3">
                            {{ $loan_helper->formatCurrency($statementSummary->internalTransferCredits) }}</p>
                    </div>
                    <div class="me-3">
                        <p class="fw-semibold fs-13 mb-1">Account Movement
                        </p>
                        <p class="text-muted mb-3">
                            {{ $loan_helper->formatCurrency($statementSummary->accountMovement) }}
                        </p>
                    </div>
                    <div class="me-3">
                        <p class="fw-semibold fs-13 mb-1">Days In Negative
                        </p>
                        <p class="text-muted mb-3">{{ $statementSummary->daysInNegative }}</p>
                    </div>
                </div>
            </div>
            <div class="col-xxl-12 col-xl-12 mt-2">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            Transactions
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
                                        <th>Category</th>
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
                                                if (isset($tag->category)) {
                                                    $category = $tag->category;
                                                }
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $transactions->date }}</td>
                                            <td style="max-width:200px; text-wrap: wrap;">{{ $category ?? 'N/A' }}</td>
                                            <td style="max-width:200px; text-wrap: wrap;">
                                                {{ $transactions->description }}
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
        </div>
    </div>
@endif
