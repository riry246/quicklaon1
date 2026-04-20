<div class="col-xl-12">
    <div class="card custom-card">
        <div class="card-header justify-content-between border-bottom-1 px-0">
            <div class="card-title">
                <i class="bi bi-wallet2 mx-2"></i> Cash Assets
            </div>
            <a href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseExample1" aria-expanded="true"
                aria-controls="collapseExample1" class="">
                <i class="ri-arrow-down-s-line fs-18 collapse-open"></i>
                <i class="ri-arrow-up-s-line collapse-close fs-18"></i>
            </a>
        </div>
        <div class="collapse show" id="collapseExample1" style="">
            <div class="card-body px-0">
                <div class="table-responsive ">
                    <table class="table table-search text-nowrap ">
                        <thead>
                            <tr>
                                <th>Institution</th>
                                <th>Type</th>
                                <th>Product name</th>
                                <th>Curent Balance</th>
                                <th>Available Funds</th>
                                <th>Min Balance <br /> (Last 6 Months)</th>
                                <th>Max Balance <br /> (Last 6 Months)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $balance = 0;
                                $availableFunds = 0;
                                $minBalance = 0;
                                $maxBalance = 0;
                            @endphp
                            @forelse ($statements->main->assets as $asset)
                                @php
                                    $balance = $asset->balance + $balance;
                                    $availableFunds = $asset->availableFunds + $availableFunds;
                                    $minBalance = $asset->previous6Months->minBalance + $minBalance;
                                    $maxBalance = $asset->previous6Months->maxBalance + $maxBalance;
                                @endphp
                                <tr>
                                    <td>{{ $asset->institution }}</td>
                                    <td>{{ $asset->account->type }} {{ $asset->type }}</td>
                                    <td>{{ $asset->account->product }}</td>
                                    <td>{{ $loan_helper->formatCurrency($asset->balance) }}</td>
                                    <td>{{ $loan_helper->formatCurrency($asset->availableFunds) }}</td>
                                    <td>{{ $loan_helper->formatCurrency($asset->previous6Months->minBalance) ?? 'N/A' }}</td>
                                    <td>{{ $loan_helper->formatCurrency($asset->previous6Months->maxBalance) ?? 'N/A' }}</td>
                                </tr>
                            @empty
                            @endforelse
                            <tr>
                                <td colspan="3"></td>
                                <td class="fw-bold">{{ $loan_helper->formatCurrency($balance) }}</td>
                                <td class="fw-bold">{{ $loan_helper->formatCurrency($availableFunds) }}</td>
                                <td class="fw-bold">{{ $loan_helper->formatCurrency($minBalance) ?? 'N/A' }}</td>
                                <td class="fw-bold">{{ $loan_helper->formatCurrency($maxBalance) ?? 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
