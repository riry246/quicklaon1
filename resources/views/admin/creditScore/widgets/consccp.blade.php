<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">
                    CURRENT CONSUMER CREDIT PROVIDER DATA BAND
                </div>
            </div>
            <div class="card-body">
                <div class="deleted-table table-responsive">
                    <div class="text-center">
                    </div>
                    <table id="delete-datatables" class="table table-search text-nowrap mt-4 mb-4">
                        <thead>
                            <tr>
                                <th>CreditPurpose</th>
                                <th>CreditorName</th>
                                <th>CreditorIndustryCd</th>
                                <th>AccountNumber</th>
                                <th>AccountNumberSubID</th>
                            </tr>
                        </thead>
                        <tbody>
                         @if(isset($summary->CONSCCP))
                            @foreach ($summary->CONSCCP as $ccpItem)
                                <tr>
                                    <td>{{ $ccpItem->CreditPurpose == 1 ? 'Consumer Purpose' : 'Not Available' }}</td>
                                    <td>{{ $ccpItem->CreditorName }}</td>
                                    <td>{{ $loan_helper->getIndustry($ccpItem->CreditorIndustryCd) ?? 'Unknown Industry' }}
                                    </td>
                                    <td>{{ $ccpItem->AccountNumber }}</td>
                                    <td>{{ $ccpItem->AccountNumberSubID }}</td>
                                </tr>
                            @endforeach
                            
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
