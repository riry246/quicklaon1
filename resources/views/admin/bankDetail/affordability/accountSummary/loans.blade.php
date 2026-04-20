@if (isset($statements->main->liabilities->loan))
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between border-bottom-1 px-0">
                <div class="card-title">
                    <i class="bi bi-coin mx-2"></i> Loans
                </div>
                <a href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseExample4"
                    aria-expanded="true" aria-controls="collapseExample4" class="">
                    <i class="ri-arrow-down-s-line fs-18 collapse-open"></i>
                    <i class="ri-arrow-up-s-line collapse-close fs-18"></i>
                </a>
            </div>
            <div class="collapse show" id="collapseExample4" style="">
                <div class="card-body px-0">
                    <div class="table-responsive ">
                        <table class="table table-search text-nowrap ">
                            <thead>
                                <tr>
                                    <th>Institution</th>
                                    <th>Type</th>
                                    <th>Product name</th>
                                    <th>Arrears <br /> (6 Months)</th>
                                    <th>Balance</th>
                                    <th>Available Funds</th>
                                    <th>Total Reypayments <br /> (Last Month)</th>
                                    <th>Total Intrest<br /> (Last Month)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $balance = 0;
                                    $availableFunds = 0;
                                    $totalRepayments = 0;
                                    $totalInterest = 0;
                                @endphp
                                @forelse ($statements->main->liabilities->loan as $asset)
                                    @php
                                        $balance = $asset->balance + $balance;
                                        $availableFunds = $asset->availableFunds + $availableFunds;
                                        $totalRepayments = $asset->previousMonth->totalRepayments + $totalRepayments;
                                        $totalInterest = $asset->previousMonth->totalInterestCharged + $totalInterest;

                                    @endphp
                                    <tr>
                                        <td>{{ $asset->institution }}</td>
                                        <td>{{ $asset->account->type }}</td>
                                        <td>{{ $asset->account->product }}</td>
                                        <td>{{ $asset->previous6Months->arrears }}</td>
                                        <td>{{ $loan_helper->formatCurrency($asset->balance) }}</td>
                                        <td>{{ $loan_helper->formatCurrency($asset->availableFunds) }}</td>
                                        <td>{{ $loan_helper->formatCurrency($asset->previousMonth->totalRepayments) }}</td>
                                        <td>{{ $loan_helper->formatCurrency($asset->previousMonth->totalInterestCharged) }}</td>
                                    </tr>
                                @empty
                                @endforelse
                                <tr>
                                    <td colspan="4"></td>
                                    <td class="fw-bold">{{ $loan_helper->formatCurrency($balance) }}</td>
                                    <td class="fw-bold">{{ $loan_helper->formatCurrency($availableFunds) }}</td>
                                    <td class="fw-bold">{{ $loan_helper->formatCurrency($totalRepayments) ?? 'N/A' }}</td>
                                    <td class="fw-bold">{{ $loan_helper->formatCurrency($totalInterest) ?? 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
