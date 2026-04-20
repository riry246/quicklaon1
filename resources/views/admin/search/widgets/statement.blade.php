<div class="row">
    <div class="col-xxl-12 col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">
                    {!! $loan_helper->highlightKeywords($tab['label'], $keyword) !!} Lists ({!! $loan_helper->highlightKeywords(count(${$tab['id']}), $keyword) !!})
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="delete-datatable" class="table table-search text-nowrap  mt-4 mb-4">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ID</th>
                                <th>Description</th>
                                <th>Transaction ID</th>
                                <th>Debit</th>
                                <th>Credit</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1; @endphp
                            @foreach ($transactions as $l)
                                <tr>
                                    <td>{!! $loan_helper->highlightKeywords($i++, $keyword) !!}</td>
                                    <td>#{!! $loan_helper->highlightKeywords($l->id, $keyword) !!}</td>
                                    <td>
                                        <p>{!! $loan_helper->highlightKeywords($l->description, $keyword) !!}</p>
                                    </td>

                                    <td>#{!! $loan_helper->highlightKeywords($l->transaction_id, $keyword) !!}</td>
                                    <td>
                                        @if ($l->type == 'Debit')
                                            <span class="text-success">+
                                                ${!! $loan_helper->highlightKeywords($l->amount, $keyword) !!}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($l->type == 'Credit')
                                            <span class="text-danger">- ${!! $loan_helper->highlightKeywords($l->amount, $keyword) !!}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($l->status == 'Complete')
                                            <span class="badge bg-success-transparent">{!! $loan_helper->highlightKeywords($l->status, $keyword) !!}</span>
                                        @elseif($l->status == 'WaitingOnClearedFunds')
                                            <span class="badge bg-warning-transparent">{!! $loan_helper->highlightKeywords($l->status, $keyword) !!}</span>
                                        @elseif($l->status == 'Dishonoured')
                                            <span class="badge bg-danger-transparent">{!! $loan_helper->highlightKeywords($l->status, $keyword) !!}</span>
                                        @endif
                                    </td>
                                    <td>{!! $loan_helper->highlightKeywords($loan_helper->formateDateTime($l->created_at), $keyword) !!}</td>
                                    <td>
                                        <div class="mb-md-0 mb-2">
                                            <a target="_blank" href="{!! $loan_helper->highlightKeywords(route('transaction.view', $l->id), $keyword) !!}"
                                                class="btn btn-icon btn-success-transparent rounded-pill btn-wave"
                                                title="Edit">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        </div>
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
