<div class="row">
    <div class="col-xxl-12 col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">
                    Document
                </div>
            </div>
            <div class="card-body">
                <table class="table table-search text-nowrap  mt-4 mb-4">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Document Type</th>
                            <th>Uplaoded at</th>
                            <th>Status</th>
                            <th>Document</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($document as $ls)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $ls['document_type'] }}</td>
                                <td>{{ $ls['updated_at'] }}</td>
                                <td>{{ ucfirst($ls['status']) }}</td>
                                <td><a target="_blank"
                                        href="{{ Storage::url('public/document/'. $ls['filename']) }}" >{{$ls['filename']}}</a>
                                </td>
                                <td>Coming Soon</td>

                            </tr>
                        @endforeach
                       

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
