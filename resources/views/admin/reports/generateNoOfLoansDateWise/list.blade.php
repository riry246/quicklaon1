<div class="col-xl-12">
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">{{ $report_type->name }} ({{ $loan_helper->formateDate($date['dateForm']) }} -
                {{ $loan_helper->formateDate($date['dateTo']) }})</div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-search mb-4 mt-4" aria-describedby="file-export_info">
                    <thead>
                        <tr>
                            <th class="text-muted">Date</th>
                            <th class="text-muted">Active</th>
                            <th class="text-muted">Completed</th>
                            <th class="text-muted">Declined</th>
                            <th class="text-muted">Incomplete</th>
                            <th class="text-muted">Processing</th>
                            <th class="text-muted">Pending</th>
                            <th class="text-muted">Total</th>

                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($report as $k => $v)
                        @if(isset($v['statuswise']))
                            <tr class="bg-light">
                                <td class="fw-bold">{{ $k }}</td>
                                @foreach($v['statuswise'] as $s)
                                    <td>{{ $s }}</td>
                                @endforeach
                                <td class="fw-bold"> {{$v['created_loans_count']}}</td>
                            </tr>
                        @endif
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>
