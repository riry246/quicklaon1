@php
    $grandTotal = 0;
    $grandMonthly = 0;
@endphp
<div class="deleted-table table-responsive col-md-6">
    <table id="delete-datatable" class="table table-search- text-nowrap mt-4 mb-4 mb-4">
        <thead>
            <tr>
                <th colspan='3' class="fw-semibold text-dark mb-0">Expenses</th>
            </tr>
        </thead>
        <tbody>
            <tr class="bg-light">
                <td class="fw-semibold text-dark mb-0">Particular</td>
                <td class="fw-semibold text-dark mb-0">Bank Statement Calulator</td>
                <td class="fw-semibold text-dark mb-0">Declared</td>
            </tr>
            @php
                $i = 0;
            @endphp
            @foreach ($expenses as $k => $v)
                <tr>
                    <td colspan="3" class="bg-primary">{{ $loan_helper->beautifyVariableName($k) }}</td>
                </tr>
                
                    @foreach ($v as $key => $y)
                    <tr>
                    <td>{{ $key }}</td>
                        @foreach ($y as $t)
                            @if (isset($t->analysisCategory->analysisPoints))
                                @foreach ($t->analysisCategory->analysisPoints as $point)
                                    @if ($point->name === 'monthlyAmountAverage')
                                        @php
                                            $grandMonthly += $point->value;
                                        @endphp
                                        <td>{{ $loan_helper->formatCurrency($point->value) }}</td>
                                    @endif
                                    @if ($point->name === 'averageDebitAmount')
                                        @php
                                            $grandMonthly += $point->value;
                                        @endphp
                                        <td>{{ $loan_helper->formatCurrency($point->value) }}</td>
                                    @endif
                                @endforeach
                            @else
                                @php
                                    $grandTotal += $t;
                                @endphp
                                <td>{{ $loan_helper->formatCurrency($t) ?? 'N/A' }}</td>
                            @endif
                        @endforeach
                         </tr>
                    @endforeach
               
            @endforeach
            <tr class="bg-secondary">
                <td>Total</td>
                <td>{{ $loan_helper->formatCurrency($grandMonthly) }}</td>
                <td>{{ $loan_helper->formatCurrency($grandTotal) }}</td>
            </tr>
        </tbody>
    </table>
</div>
