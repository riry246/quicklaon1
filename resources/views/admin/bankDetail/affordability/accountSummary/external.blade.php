@if (isset($statements->main->external))
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between border-bottom-1 px-0">
                <div class="card-title">
                    <i class="bi bi-coin mx-2"></i> External
                </div>
                <a href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseExample3"
                    aria-expanded="true" aria-controls="collapseExample3" class="">
                    <i class="ri-arrow-down-s-line fs-18 collapse-open"></i>
                    <i class="ri-arrow-up-s-line collapse-close fs-18"></i>
                </a>
            </div>
            <div class="collapse show" id="collapseExample3" style="">
                <div class="card-body px-0">
                    <div class="table-responsive ">
                        <table class="table table-search text-nowrap ">
                            <thead>
                                <tr>
                                    <th>Source</th>
                                    <th>First Reypayment</th>
                                    <th>Last Reypayment</th>
                                    <th>No. Occurances</th>
                                    <th>Avg Amount</th>
                                    <th>Avg Amount Monthly</th>
                                    <th>Total Reypayments</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $avg = 0;
                                    $avgMonthly = 0;
                                    $totalRepayments = 0;
                                @endphp
                                @forelse ($statements->main->external as $assets)
                                    @php
                                        $avg = $assets->payments->amountAvg + $avg;
                                        $avgMonthly = $assets->payments->amountAvgMonthly + $avgMonthly;
                                        $totalRepayments = $assets->payments->total + $totalRepayments;
                                    @endphp
                                    <tr>
                                        <td>{{ ucfirst($assets->source) }}</td>
                                        <td>{{ $loan_helper->formateDate($assets->payments->first) }}</td>
                                        <td>{{ $loan_helper->formateDate($assets->payments->last) }}</td>
                                        <td>{{ $assets->payments->noOccurrences }}</td>
                                        <td>{{ $loan_helper->formatCurrency($assets->payments->amountAvg) }}</td>
                                        <td>{{ $loan_helper->formatCurrency($assets->payments->amountAvgMonthly) }}</td>
                                        <td>{{ $loan_helper->formatCurrency($assets->payments->total) }}</td>
                                    </tr>
                                @empty
                                @endforelse
                                <tr>
                                    <td colspan="4"></td>
                                    <td class="fw-bold">{{ $loan_helper->formatCurrency($avg) }}</td>
                                    <td class="fw-bold">{{ $loan_helper->formatCurrency($avgMonthly) }}</td>
                                    <td class="fw-bold">{{ $loan_helper->formatCurrency($totalRepayments) ?? 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
