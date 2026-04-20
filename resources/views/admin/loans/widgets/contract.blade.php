<div class="row">
    <div class="col-xxl-12 col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">
                    Loan Contract
                </div>
            </div>
            <div class="card-body">
                <table class="table table-search  mt-4 mb-4">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ID</th>
                            <th>Status</th>
                            <th>Sent At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($contract as $ls)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $ls->ref_code }}</td>
                                <td>{{ $ls->status }}</td>
                                <td>{{ $loan_helper->formateDateTime($ls->created_at) }}</td>
                                <td>
                                    @if ($ls->status == 'completed')
                                        <a target="_blank" href="{{ route('contract.download', $ls->ref_code) }}"
                                            class="btn btn-secondary"><i class="bx bxs-download"></i></a>
                                    @endif
                                    <a target="_blank" href="{{ route('contract', $ls->ref_code) }}"
                                        class="btn btn-success"><i class="bx bxs-pen"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
