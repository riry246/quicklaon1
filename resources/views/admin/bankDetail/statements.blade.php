<div class="row">
    <div class="col-xxl-12 col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">
                    Overall Statement
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
                                <th>Account</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Debit</th>
                                <th>Credit</th>                        

                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($statements as $key => $s)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $s->postDate }}</td>
                                    @foreach ($accounts as $account)
                                    @if($account->id == $s->account)
                                    <td>{{ $account->name }}</td>
                                    @endif
                                    @endforeach
                                    <td>{{ $s->description }}</td>
                                    <td>{{ $s->status }}</td>
                                    @if($s->direction == 'debit')
                                    <td>{{ $s->amount }}</td>
                                    @else
                                    <td></td>
                                    @endif
                                    @if($s->direction == 'credit')
                                    <td>{{ $s->amount }}</td>
                                    @else
                                    <td></td>
                                    @endif
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
