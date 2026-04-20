<div class="row">
    <div class="col-xxl-12 col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">
                    {{ $tab['label'] }} Lists ({{ count(${$tab['id']}) }})
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="delete-datatable" class="table table-search text-nowrap  mt-4 mb-4">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Application Id</th>
                                <th>User</th>
                                <th>Amount</th>
                                <th>Duration</th>
                                <th>Frequency</th>
                                <th>Lead Id</th>
                                <th>Approved Amount</th>
                                <th>Status</th>
                                <th>Application Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1; @endphp
                            @foreach ($loan_applications as $item)
                                @if (isset($item->leadMarketBuy->lead_id))
                                    <tr style="background:#282c30">
                                    @elseif($item->user->risk_flag)
                                    <tr style="background:red">
                                    @else
                                    <tr>
                                @endif
                                <td>{{ $i++ }}</td>
                                <td>#{!! $loan_helper->highlightKeywords($item->id, $keyword) !!}</td>
                                <td>{!! $loan_helper->highlightKeywords($loan_helper->getUserName($item->user_id), $keyword) !!}</td>
                                <td>{!! $loan_helper->highlightKeywords($loan_helper->formatCurrency($item->amount), $keyword) !!}</td>
                                <td>{!! $loan_helper->highlightKeywords(ucfirst($item->duration), $keyword) !!} weeks</td>
                                <td>{!! $loan_helper->highlightKeywords(ucfirst($item->frequency), $keyword) !!}</td>
                                <td>
                                    @if ($item->leadMarketBuy)
                                        {!! $loan_helper->highlightKeywords($item->leadMarketBuy->lead_id, $keyword) !!}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{!! $loan_helper->highlightKeywords($loan_helper->formatCurrency($item->approved_amount), $keyword) !!}</td>

                                @if ($item->status == 'active' || $item->status == 'Complete')
                                    <td>
                                        <span class="badge bg-success p-2 fs-12">{!! $loan_helper->highlightKeywords(ucfirst($item->status), $keyword) !!}</span>
                                    </td>
                                @elseif($item->status == 'processing')
                                    <td>
                                        <span class="badge bg-primary p-2 fs-12">{!! $loan_helper->highlightKeywords(ucfirst($item->status), $keyword) !!}</span>
                                    </td>
                                @elseif($item->status == 'incomplete')
                                    <td>
                                        <span class="badge bg-warning p-2 fs-12">{!! $loan_helper->highlightKeywords(ucfirst($item->status), $keyword) !!}</span>
                                    </td>
                                @elseif($item->status == 'pending')
                                    <td>
                                        <span class="badge bg-dark p-2 fs-12">{!! $loan_helper->highlightKeywords(ucfirst($item->status), $keyword) !!}</span>
                                    </td>
                                @else
                                    <td>
                                        <span class="badge bg-danger p-2  fs-12">{!! $loan_helper->highlightKeywords(ucfirst($item->status), $keyword) !!}</span>
                                    </td>
                                @endif
                                <td>{!! $loan_helper->highlightKeywords($loan_helper->formateDateTime($item->application_date), $keyword) !!}</td>
                                <td>
                                    <div class="mb-md-0 mb-2">
                                        <a href="{{ route('loan.view', ['id' => $item->id]) }}"
                                            class="btn btn-icon btn-success-transparent rounded-pill btn-wave">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        @if (isset($item->leadMarketBuy->lead_id))
                                            <a href="{{ route('leadmarket.bought.view', ['id' => $item->leadMarketBuy->id]) }}"
                                                class="btn btn-icon btn-secondary-transparent rounded-pill btn-wave">
                                                <i class="ri-flag-line"></i>
                                            </a>
                                        @endif
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
