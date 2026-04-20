<table class="table  text-nowrap mt-4 mb-4">
    <thead>
        <tr>
            <th>Ref</th>
            <th>Description</th>
            <th>Value</th>
        </tr>
    </thead>
    <tbody>
        @foreach (json_decode($illionCustomerInfo->decisionMetrics) as $metrics)
            <tr>
                <td>{{ $metrics->id }}</td>
                <td>{{ $metrics->name }}</td>
                @if($metrics->type == 'money')
                <td>{{ $loan_helper->formatCurrency($metrics->value) }}</td>
                @else
                 <td>{{ $metrics->value }}</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
