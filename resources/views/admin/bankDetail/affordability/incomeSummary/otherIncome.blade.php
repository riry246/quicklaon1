@if (isset($statements->income->otherCredit))
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between border-bottom-1 px-0">
                <div class="card-title">
                    <i class="bi bi-graph-up-arrow mx-2"></i> Other Income Source
                    @if (count($statements->income->otherCredit) < 1)
                        - NO OTHER INCOME FOUND
                    @endif
                </div>
                <a href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseExample7"
                    aria-expanded="true" aria-controls="collapseExample7" class="">
                    <i class="ri-arrow-down-s-line fs-18 collapse-open"></i>
                    <i class="ri-arrow-up-s-line collapse-close fs-18"></i>
                </a>
            </div>
            @if (count($statements->income->otherCredit) > 1)
                <div class="collapse show" id="collapseExample7" style="">
                    <div class="card-body px-0">
                        <div class="table-responsive ">
                            <table class="table table-search text-nowrap ">
                                <thead>
                                    <tr>
                                        <th>Source</th>
                                        <th>Duration (Days)</th>
                                        <th>Average Amount</th>
                                        <th>No. Occurrences</th>
                                        <th>Avg. Monthly Occurance Amount</th>
                                        <th>Last Amount</th>
                                        <th>Last Occurance</th>
                                        <th>Other Credit Label</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($statements->income->otherCredit as $assets)
                                        <tr>
                                            <td>{{ isset($assets->source) ? ucfirst($assets->source) : 'N/A' }}</td>
                                            <td>{{ isset($assets->ageDays) ? ucfirst($assets->ageDays) : 'N/A' }}</td>
                                            <td>{{ isset($assets->amountAvg) ? $loan_helper->formatCurrency($assets->amountAvg) : 'N/A' }}
                                            </td>
                                            <td>{{ isset($assets->noOccurrences) ? $assets->noOccurrences : 'N/A' }}
                                            </td>
                                            <td>{{ isset($assets->avgMonthlyOccurence) ? $loan_helper->formatCurrency($assets->avgMonthlyOccurence) : 'N/A' }}
                                            </td>
                                            <td>{{ isset($assets->current->amount) ? $loan_helper->formatCurrency($assets->current->amount) : 'N/A' }}
                                            </td>
                                            <td>{{ isset($assets->current->date) ? $loan_helper->formateDate($assets->current->date) : 'N/A' }}
                                            </td>
                                            <td>{{ isset($assets->current->otherCreditLabel) ? $assets->current->otherCreditLabel : 'N/A' }}
                                            </td>


                                        </tr>
                                    @empty
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif
