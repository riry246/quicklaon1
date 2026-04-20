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
                                <th>Company Name</th>
                                <th>Enquiry Date</th>
                                <th>Enquiry Purpose</th>
                                <th>Enquiry Amount</th>
                                <th>Application Role</th>
                                <th>EnqCreditPurpose</th>

                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($summary->CONSCCP))
                                @foreach ($summary->ENQRHIST as $e)
                                    <tr>
                                        <td>{{ $e->BureauMemberName }}</td>
                                        <td>{{ $e->EnquiryDate }}</td>
                                        <td>{{ $e->EnquiryPurposeCd == 1
                                            ? 'Application for New Credit'
                                            : ($e->EnquiryPurposeCd == 2
                                                ? 'Application for Credit Increase'
                                                : ($e->EnquiryPurposeCd == 3
                                                    ? 'Credit Review'
                                                    : ($e->EnquiryPurposeCd == 4
                                                        ? 'Collection of Debt'
                                                        : 'Unknown Purpose'))) }}
                                        </td>
                                        <td>{{ $e->EnquiryConsiderationAmt }}</td>
                                        <td>{{ $e->ApplicationRole == 1
                                            ? 'Principal'
                                            : ($e->ApplicationRole == 2
                                                ? 'Joint'
                                                : ($e->ApplicationRole == 3
                                                    ? 'Co-Borrower'
                                                    : ($e->ApplicationRole == 4
                                                        ? 'Guarantor'
                                                        : ($e->ApplicationRole == 5
                                                            ? 'Individual on a Business Account'
                                                            : ($e->ApplicationRole == 6
                                                                ? 'Director'
                                                                : 'Unknown Role'))))) }}
                                        </td>
                                        <td>{{ $e->EnqCreditPurpose == 1
                                            ? 'Consumer Purpose'
                                            : ($e->EnqCreditPurpose == 2
                                                ? 'Commercial Purpose'
                                                : 'Unknown Purpose') }}
                                        </td>

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
