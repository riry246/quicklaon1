<div class="card custom-card p-0 m-0 ">
 <div class="card-header">
        <div class="card-title">
            <i class="bi bi-graph-up-arrow mx-2"></i>Metrics
        </div>
    </div>
    <div class="card-body mb-0">
        @foreach ($metrics as $k => $v)
        @if($k == 'Summary')
        @else
            <h5 class="mt-4 mb-3"> {{ $k }}</h5>
            <div class="deleted-table table-responsive">
                <table id="delete-datatable" class="table table-search- text-nowrap mt-4 mb-4 mb-4">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Decision Point</th>
                            <th>Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($v as $e)
                            <tr class="text-dark">
                                <td>{{ $e->id }}</td>
                                <td>{{ $e->title }}<br />
                                    <span>{{ $e->description }}</span>
                                </td>
                                <td>
                                    @if ($e->result->format == 'money')
                                        {{ $loan_helper->formatCurrency($e->result->value) }}
                                    @elseif($e->result->format == 'percent')
                                        {{ $e->result->value }} %
                                    @else
                                        {{ $e->result->value }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <hr />
        @endif
        @endforeach
    </div>
</div>
