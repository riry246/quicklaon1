<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-body">
                <div class="deleted-table table-responsive">
                    <div class="text-center">
                    </div>
                    <table id="delete-datatables" class="table table-search text-nowrap mt-4 mb-4">
                        <thead>
                            <tr>

                                <th>Property Name</th>
                                <th>Address</th>
                                <th>Reported Since Date</th>
                                <th>Addr Date</th>
                                <th>BestAddrInd</th>
                                <th>EnqAddMatchind</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($summary->PERSONPF->PADDRESS as $a)
                                <tr>
                                    <td>{{ $a->PropertyName ? $a->PropertyName : 'Not Available' }}</td>
                                    <td>{{ $a->AddressLine1 }},<br />
                                        {{ $a->LocalityName ? $a->LocalityName : '' }}
                                        {{ $a->State ? $a->State . ', ' : '' }}
                                        {{ $a->PostalCode ? $a->PostalCode . ' ' : '' }}
                                        {{ $a->CountryCode }}</td>
                                    <td>{{ $a->ReportedSinceDate }}</td>
                                    <td>{{ $a->AddrDate }}</td>
                                    <td>{{ $a->BestAddrInd }}</td>
                                    <td>{{ $a->EnqAddMatchind }}</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
