<div class="row">
    <div class="col-xxl-12 col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">
                    ID Verification
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="delete-datatable" class="table table-search text-nowrap  mt-4 mb-4">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ID Type</th>
                                <th>ID information</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Api Response</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($idverification as $ls)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $ls['id_type'] }}</td>
                                    @php
                                        $decodedData = json_decode($ls['id_information'], true);
                                        $apiResponse = json_decode($ls['api_response'], true);

                                        $name = null;
                                        $dob = null;
                                        foreach ($decodedData as $k => $v) {
                                            if ($k == 'GivenName' || $k == 'MiddleName' || $k == 'FamilyName') {
                                                $name .= $v . ' ';
                                            } elseif ($k == 'BirthDate') {
                                                $dob = date('d-m-Y', strtotime($v));
                                            }
                                        }

                                    @endphp
                                    <td>
                                        <p> {{ $name }} </p>
                                        <p> DOB: {{ $dob }} </p>
                                        @foreach ($decodedData as $k => $v)
                                            @if ($k == 'GivenName' || $k == 'MiddleName' || $k == 'FamilyName' || $k == 'BirthDate')
                                            @else
                                                <p>{{ $k }} : {{ $v }}</p>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>Uploaded At: <br />
                                        {{ $loan_helper->formateDateTime($ls['created_at']) }} <br /> <br />
                                        @if($ls['verified_date'])
                                        Verified At: <br />
                                        {{ $loan_helper->formateDateTime($ls['verified_date']) ?? 'N/A' }}</td>
                                        @endif
                                    <td>{{ ucfirst($ls['status']) }}</td>
                                    <td>
                                        @if (isset($apiResponse['VerifyDocumentResult']['Errors']))
                                            @foreach ($apiResponse['VerifyDocumentResult']['Errors'] as $error)
                                                <p>{{ $error['Message'] }}</p>
                                            @endforeach
                                        @else
                                            Not Available
                                        @endif
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
