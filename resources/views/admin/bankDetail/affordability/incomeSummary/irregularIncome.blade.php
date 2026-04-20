@if (isset($statements->income->irregular))
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between border-bottom-1 px-0">
                <div class="card-title">
                    <i class="bi bi-graph-up-arrow mx-2"></i> Variable Income Source
                    @if (count($statements->income->irregular) < 1)
                        - NO VARIABLE INCOME FOUND
                    @endif
                </div>
                <a href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseExample6"
                    aria-expanded="true" aria-controls="collapseExample6" class="">
                    <i class="ri-arrow-down-s-line fs-18 collapse-open"></i>
                    <i class="ri-arrow-up-s-line collapse-close fs-18"></i>
                </a>
            </div>
            @if (count($statements->income->irregular) > 1)
                <div class="collapse show" id="collapseExample6" style="">
                    <div class="card-body px-0">
                        <div class="table-responsive ">
                            <table class="table table-search text-nowrap ">
                                <thead>
                                    <tr>
                                        <th>Source</th>
                                        <th>Frequency</th>
                                        <th>Duration (Days)</th>
                                        <th>Average Amount <br /> (3 Months)</th>
                                        <th>Average Amount Monthly <br /> (3 Months)</th>
                                        <th>Variance Amount</th>
                                        <th>Last Amount</th>
                                        <th>Last Occurance</th>
                                        <th>Next Occurance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($statements->income->irregular as $assets)
                                        <tr>
                                            <td>{{ isset($assets->source) ? ucfirst($assets->source) : 'N/A' }}</td>
                                            <td>{{ isset($assets->frequency) ? ucfirst($assets->frequency) : 'N/A' }}
                                            </td>
                                            <td>{{ isset($assets->ageDays) ? ucfirst($assets->ageDays) : 'N/A' }}</td>
                                            <td>{{ isset($assets->previous3Months->amountAvg) ? $loan_helper->formatCurrency($assets->previous3Months->amountAvg) : 'N/A' }}
                                            </td>
                                            <td>{{ isset($assets->previous3Months->amountAvgMonthly) ? $loan_helper->formatCurrency($assets->previous3Months->amountAvgMonthly) : 'N/A' }}
                                            </td>
                                            <td>{{ isset($assets->previous3Months->variance) ? $loan_helper->formatCurrency($assets->previous3Months->variance) : 'N/A' }}
                                            </td>
                                            <td>{{ isset($assets->current->amount) ? $loan_helper->formatCurrency($assets->current->amount) : 'N/A' }}
                                            </td>
                                            <td>{{ isset($assets->current->date) ? $loan_helper->formateDate($assets->current->date) : 'N/A' }}
                                            </td>
                                            <td>{{ isset($assets->current->nextDate) ? $loan_helper->formateDate($assets->current->nextDate) : 'N/A' }}
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
