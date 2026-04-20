<div class="card custom-card p-0 m-0 ">
    <div class="card-header">
        <div class="card-title">
            <i class="bi bi-graph-up-arrow mx-2"></i>Income
        </div>
    </div>
    <div class="card-body mb-0">
        @foreach ($group->Income as $k => $v)
            @if (count($v->subgroup) > 0)
                <h5 class="mt-4 mb-3"> {{ $v->title }} ({{ $v->id }})</h5>
                <div class="deleted-table table-responsive">
                    <table id="delete-datatable" class="table table-search- text-nowrap mt-4 mb-4 mb-4">
                        <thead>
                            <tr>
                                <th>Account</th>
                                <th>#Txs</th>
                                <th>$ Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $transaction = 0;
                                $totalamount = 0;
                            @endphp
                            @foreach ($v->subgroup as $s)
                                <tr class="text-dark">
                                    <td>{{ $s->name }}</td>
                                    <td>{{ count($s->transactions) }}</td>
                                    @php
                                        $transaction = count($s->transactions) + $transaction;
                                        $total = 0;
                                    @endphp
                                    @foreach ($s->transactions as $t)
                                        @php
                                            $total = $t->amount + $total;
                                            $totalamount = $t->amount + $totalamount;
                                        @endphp
                                    @endforeach
                                    <td>
                                        <a class="dropdown-item" href="javascript:void(0);"
                                            onclick="toggleTable('transaction_{{ $s->id }}')">{{ $loan_helper->formatCurrency($total) }}
                                            <i class="bi bi-arrow-down"></i></a>
                                    </td>
                                </tr>
                                <tr id="transaction_{{ $s->id }}" class="transactions_details"
                                    style="display:none">
                                    <td colspan="3">
                                        <table class="table table-search- text-nowrap mt-4 mb-4 mb-4">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Description</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($s->transactions as $t)
                                                    <tr>
                                                        <td>
                                                            {{ $loan_helper->formateDateTime($t->date) }}
                                                        </td>
                                                        <td>
                                                            {{ $t->description }}
                                                        </td>
                                                        <td>
                                                            {{ $loan_helper->formatCurrency($t->amount) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="fw-bold">Total</td>
                                <td class="fw-bold">{{ $transaction }}</td>
                                <td class="fw-bold">{{ $loan_helper->formatCurrency($totalamount) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr />
            @endif
        @endforeach
    </div>
</div>
