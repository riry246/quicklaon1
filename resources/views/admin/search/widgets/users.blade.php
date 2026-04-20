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
                                <th>Customer ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Status</th>
                                <th>Created_at</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1; @endphp
                            @foreach ($users as $item)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>#{!! $loan_helper->highlightKeywords($item->id, $keyword) !!}</td>
                                    <td>{!! $loan_helper->highlightKeywords($loan_helper->getUserName($item->id), $keyword) !!}</td>
                                    <td>{!! $loan_helper->highlightKeywords($item->email, $keyword) !!}</td>
                                    <td>{!! $loan_helper->highlightKeywords($item->mobile, $keyword) !!}</td>
                                    @if ($item->status == 'active' || $item->status == 'Complete')
                                        <td>
                                            <span class="badge bg-success p-2 fs-12">{!! $loan_helper->highlightKeywords(ucfirst($item->status), $keyword) !!}</span>
                                        </td>
                                    @else
                                        <td>
                                            <span class="badge bg-danger p-2  fs-12">{!! $loan_helper->highlightKeywords(ucfirst($item->status), $keyword) !!}</span>
                                        </td>
                                    @endif
                                    <td>{!! $loan_helper->highlightKeywords($loan_helper->formateDateTime($item->created_at), $keyword) !!}</td>
                                    <td>
                                        <div class="mb-md-0 mb-2">
                                            <a href="{{ route('customer.view', $item->id) }}"
                                                class="btn btn-icon btn-success-transparent rounded-pill btn-wave"
                                                title="View">
                                                <i class="ri-file-history-fill"></i>
                                            </a>
                                            <a href="{{ route('customer.edit', $item->id) }}"
                                                class="btn btn-icon btn-primary-transparent rounded-pill btn-wave"
                                                title="Edit">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                            <a href="{{ route('customer.delete', $item->id) }}"
                                                class="btn btn-icon btn-danger-transparent rounded-pill btn-wave me-5"
                                                title="Delete">
                                                <i class="ri-delete-bin-line"></i>
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
