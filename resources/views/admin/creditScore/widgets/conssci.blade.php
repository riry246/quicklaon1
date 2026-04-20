<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">
                    CONSUMER SERIOUS CREDIT INFRINGEMENT DATA BAND
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
                                <th>AccountType</th>
                                <th>AccountDate</th>
                                <th>AccountStatus</th>
                                <th>AccountNumber</th>
                                <th>AccountNumberSubID</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(isset($summary->CONSSCI))
                            @foreach ($summary->CONSSCI as $conssciItem)
                                <tr>
                                    <td>{{ $conssciItem->CreditPurpose == 1 ? 'Consumer Purpose' : 'Not Available' }}
                                    </td>
                                    <td>{{ $conssciItem->CreditorName }}</td>
                                    <td>{{ $loan_helper->getIndustry($conssciItem->CreditorIndustryCd) ?? 'Unknown Industry' }}
                                    </td>
                                    <td>@php
                                        $accountTypeNames = [
                                            'AL' => 'Auto Loan',
                                            'AO' => 'All other Account Types',
                                            'BF' => 'Buy Now Pay Later Facility',
                                            'BT' => 'Buy Now Pay Later Transaction',
                                            'CA' => 'Charge Card',
                                            'CC' => 'Credit Card',
                                            'CM' => 'Chattel Mortgage',
                                            'LR' => 'Loan Retail',
                                            'LS' => 'Loan - Secured',
                                            'LT' => 'Loan - Short Term',
                                            'LU' => 'Loan - Unsecured',
                                            'OD' => 'Overdraft',
                                            'PF' => 'Personal Loan (fixed term)',
                                            'PP' => 'Peer to Peer',
                                            'PR' => 'Personal Loan (revolving)',
                                            'RA' => 'Lease, Automobile',
                                            'RE' => 'Equipment Hire or Rental',
                                            'RM' => 'Real Property Mortgage',
                                            'TC' => 'Telecommunications Services',
                                            'UA' => 'Utilities',
                                            // Add more as needed
                                        ];
                                    @endphp
                                        {{ $accountTypeNames[$conssciItem->AccountType] ?? 'Not Available' }}</td>
                                    <td>{{ $conssciItem->AccountDate }}</td>
                                    <td>
                                        @php
                                            $accountStatusNames = [
                                                'C' => 'Closed',
                                                'O' => 'Open',
                                                'S' => 'Serious Credit Infringement',
                                            ];
                                        @endphp
                                        {{ $accountStatusNames[$conssciItem->AccountStatus] ?? 'Unknown Account Status' }}
                                    </td>
                                    <td>{{ $conssciItem->AccountNumber }}</td>
                                    <td>{{ $conssciItem->AccountNumberSubID }}</td>
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
