@if (isset($statements->income->regular))
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between border-bottom-1 px-0">
                <div class="card-title">
                    <i class="bi bi-graph-up-arrow mx-2"></i> Regular Income Source
                     @if(count($statements->income->regular) < 1)
                    - NO REGULAR INCOME FOUND
                    @endif
                </div>
                <a href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseExample8"
                    aria-expanded="true" aria-controls="collapseExample8" class="">
                    <i class="ri-arrow-down-s-line fs-18 collapse-open"></i>
                    <i class="ri-arrow-up-s-line collapse-close fs-18"></i>
                </a>
            </div>
            @if(count($statements->income->regular) > 1)
            <div class="collapse show" id="collapseExample8" style="">
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
                                @forelse ($statements->income->regular as $assets)
                                    <tr>
                                        <td>{{ ucfirst($assets->source) }}</td>
                                        <td>{{ ucfirst($assets->frequency) }}</td>
                                        <td>{{ ucfirst($assets->ageDays) }}</td>
                                        <td>{{ $loan_helper->formatCurrency($assets->previous3Months->amountAvg) }}</td>
                                        <td>{{ $loan_helper->formatCurrency($assets->previous3Months->amountAvgMonthly) }}</td>
                                        <td>{{ $loan_helper->formatCurrency($assets->previous3Months->variance) }}</td>
                                        <td>{{ $loan_helper->formatCurrency($assets->current->amount) }}</td>
                                        <td>{{ $loan_helper->formateDate($assets->current->date) }}</td>
                                        <td>{{ $loan_helper->formateDate($assets->current->nextDate) }}</td>

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
