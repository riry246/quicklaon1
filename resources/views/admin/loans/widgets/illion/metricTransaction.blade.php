@php
    $id = uniqid();
@endphp
<div class="accordion accordion-solid-primary mt-3" id="accordionPrimarySolid">
    <div class="accordion-item">
        <h2 class="accordion-header" id="heading{{ $id }}">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapse{{ $id }}" aria-expanded="false"
                aria-controls="collapse{{ $id }}">
                Statement
            </button>
        </h2>
        <div id="collapse{{ $id }}" class="accordion-collapse collapse"
            aria-labelledby="heading{{ $id }}" data-bs-parent="#accordionPrimarySolid">
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
                                    @foreach ($t['transactions'] as $key => $transactions)
                                        @php
                                            foreach ($transactions['tags'] as $tag) {
                                                if (isset($tag['creditDebit'])) {
                                                    $type = $tag['creditDebit'];
                                                }
                                                if (isset($tag['category'])) {
                                                    $category = $tag['category'];
                                                }
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $transactions['date'] }}</td>
                                            <td style="max-width:200px; text-wrap: wrap;">{{ $category ?? 'N/A' }}</td>
                                            <td style="max-width:200px; text-wrap: wrap;">
                                                {{ $transactions['description'] }}
                                            </td>
                                            <td>
                                                @if ($type == 'debit')
                                                    <span class="text-danger">
                                                        {{ $loan_helper->formatCurrency($transactions['amount']) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($type == 'credit')
                                                    <span class="text-success">
                                                        {{ $loan_helper->formatCurrency($transactions['amount']) }}</span>
                                                @endif
                                            </td>
                                            <td><span
                                                    class="text-secondary">{{ $loan_helper->formatCurrency($transactions['balance']) }}</span>
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
