@if (isset($statements->main->liabilities->credit))
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between border-bottom-1 px-0">
                <div class="card-title">
                    <i class="bi bi-credit-card mx-2"></i> Credit
                </div>
                <a href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseExample2"
                    aria-expanded="true" aria-controls="collapseExample2" class="">
                    <i class="ri-arrow-down-s-line fs-18 collapse-open"></i>
                    <i class="ri-arrow-up-s-line collapse-close fs-18"></i>
                </a>
            </div>
            <div class="collapse show" id="collapseExample2" style="">
                <div class="card-body px-0">
                    <div class="table-responsive ">
                        <table class="table table-search text-nowrap ">
                            <thead>
                                <tr>
                                    <th>Institution</th>
                                    <th>Type</th>
                                    <th>Product name</th>
                                    <th>Balance</th>
                                    <th>Available Funds</th>
                                    <th>Total Credit <br /> (Last Month)</th>
                                    <th>Total Spent <br /> (Last Month)</th>
                                    <th>Min Balance <br /> (Last Month)</th>
                                    <th>Max Balance <br /> (Last Month)</th>
                                    <th>Cash Advance<br /> (Last 6 Months)</th>
                                    <th>Credit Limit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $balance = 0;
                                    $availableFunds = 0;
                                    $totalCredits = 0;
                                    $totalDebits = 0;
                                    $minBalance = 0;
                                    $maxBalance = 0;
                                    $cashAdvances = 0;
                                    $creditLimit = 0;

                                @endphp
                                @forelse ($statements->main->liabilities->credit as $asset)
                                    @php
                                        $balance = $asset->balance + $balance;
                                        $availableFunds = $asset->availableFunds + $availableFunds;
                                        $totalCredits = $asset->previousMonth->totalCredits + $totalCredits;
                                        $totalDebits = $asset->previousMonth->totalDebits + $totalDebits;
                                        $minBalance = $asset->previousMonth->minBalance + $minBalance;
                                        $maxBalance = $asset->previousMonth->maxBalance + $maxBalance;
                                        $cashAdvances = $asset->previous6Months->cashAdvances + $cashAdvances;
                                        $creditLimit = $asset->creditLimit + $creditLimit;

                                    @endphp
                                    <tr>
                                        <td>{{ $asset->institution }}</td>
                                        <td>{{ $asset->account->type }}</td>
                                        <td>{{ $asset->account->product }}</td>
                                        <td>{{ $loan_helper->formatCurrency($asset->balance) }}</td>
                                        <td>{{ $loan_helper->formatCurrency($asset->availableFunds) }}</td>
                                        <td>{{ $loan_helper->formatCurrency($asset->previousMonth->totalCredits) }}</td>
                                        <td>{{ $loan_helper->formatCurrency($asset->previousMonth->totalDebits) }}</td>
                                        <td>{{ $loan_helper->formatCurrency($asset->previousMonth->minBalance) }}</td>
                                        <td>{{ $loan_helper->formatCurrency($asset->previousMonth->maxBalance) }}</td>
                                        <td>{{ $loan_helper->formatCurrency($asset->previous6Months->cashAdvances) }}</td>
                                        <td>{{ $loan_helper->formatCurrency($asset->creditLimit) ?? 'N/A' }}</td>
                                    </tr>
                                @empty
                                @endforelse
                                <tr>
                                    <td colspan="3"></td>
                                    <td class="fw-bold">{{ $loan_helper->formatCurrency($balance) }}</td>
                                    <td class="fw-bold">{{ $loan_helper->formatCurrency($availableFunds) }}</td>
                                    <td class="fw-bold">{{ $loan_helper->formatCurrency($totalCredits) ?? 'N/A' }}</td>
                                    <td class="fw-bold">{{ $loan_helper->formatCurrency($totalDebits) ?? 'N/A' }}</td>
                                    <td class="fw-bold">{{ $loan_helper->formatCurrency($minBalance) ?? 'N/A' }}</td>
                                    <td class="fw-bold">{{ $loan_helper->formatCurrency($maxBalance) ?? 'N/A' }}</td>
                                    <td class="fw-bold">{{ $loan_helper->formatCurrency($cashAdvances) ?? 'N/A' }}</td>
                                    <td class="fw-bold">{{ $loan_helper->formatCurrency($creditLimit) ?? 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
