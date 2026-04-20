<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-body">
                <div class="deleted-table table-responsive">
                    <div class="text-center">
                    </div>
                    <table id="delete-datatable" class="table table-search text-nowrap mt-4 mb-4">
                        <thead>
                            <tr>
                                <th>AttributeName</th>
                                <th>AttributeValue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($summary->ATTRIBUTE as $a)
                                <tr>
                                    <td>{{ $a->AttributeName }}</td>
                                    <td>{{ $a->AttributeValue }}</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
