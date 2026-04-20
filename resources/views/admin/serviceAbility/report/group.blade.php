<div class="card custom-card p-0 m-0">
    <div class="card-header">
        <div class="card-title">
            Group
        </div>
    </div>
    <div class="card-body mb-0">
        @foreach ($groups as $k => $v)
        <h5 class="mt-3"> {{ $k }}</h5>
            <div class="deleted-table table-responsive">
                <table id="delete-datatable" class="table table-search- text-nowrap mt-4 mb-4 mb-4">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Decision Point</th>
                            <th>Score</th>
                            <th>format</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($v as $e)
                            <tr>
                                <td>{{ $e->id }}</td>
                                <td>{{ $e->title }}</td>
                               
                                
                            </tr>
                             @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>
</div>
