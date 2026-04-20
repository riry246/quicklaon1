<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">
                    CONSUMER DEFAULT DATA BAND
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
                                <th>AccountRelationship</th>
                                <th>AccountDate</th>
                                <th>AccountStatus</th>
                                <th>PaymentStatus</th>
                                <th>DefaultAmount</th>
                                <th>OriginalDefaultAmt</th> <!-- Corrected header -->
                                <th>OriginalDefaultDate</th> <!-- Corrected header -->
                                <th>AccountNumber</th>
                                <th>AccountNumberSubID</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($summary->CONSDFLT))
                                @foreach ($summary->CONSDFLT as $a)
                                    @foreach ($a->DFDETAIL as $b)
                                        <tr>
                                            <td>{{ $b->CreditPurpose == 1 ? 'Consumer Purpose' : ($b->CreditPurpose == 2 ? 'Commercial Purpose' : 'Not Available') }}
                                            </td>
                                            <td>{{ $b->CreditorName }}</td>
                                            <td>

                                                {{ $loan_helper->getIndustry($b->CreditorIndustryCd) ?? 'Unknown Industry' }}
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
                                                ];
                                            @endphp

                                                {{ $accountTypeNames[$b->AccountType] ?? 'Unknown Account Type' }}
                                            </td>
                                            <td>@php
                                                $accountRelationshipNames = [
                                                    1 => 'Principal',
                                                    2 => 'Joint',
                                                    3 => 'Co-Borrower',
                                                    4 => 'Guarantor',
                                                    7 => 'Individual on a Business Account',
                                                    19 => 'Director',
                                                ];
                                            @endphp

                                                {{ $accountRelationshipNames[$b->AccountRelationship] ?? 'Unknown Relationship' }}
                                            </td>
                                            <td>{{ $b->AccountDate }}</td>
                                            <td>@php
                                                $accountStatusNames = [
                                                    'C' => 'Closed',
                                                    'O' => 'Open',
                                                    'S' => 'Serious Credit Infringement',
                                                ];
                                            @endphp

                                                {{ $accountStatusNames[$b->AccountStatus] ?? 'Unknown Account Status' }}
                                            </td>
                                            <td>@php
                                                $paymentStatusNames = [
                                                    'D' => 'Current default amount is outstanding',
                                                    'P' => 'Default amount has been Paid',
                                                ];
                                            @endphp

                                                {{ $paymentStatusNames[$b->PaymentStatus] ?? 'Unknown Payment Status' }}
                                            </td>
                                            <td>{{ $b->DefaultAmount }}</td>
                                            <td>{{ $b->OriginalDefaultAmt }}</td>
                                            <td>{{ $b->OriginalDefaultDate }}</td>
                                            <td>{{ $b->AccountNumber }}</td>
                                            <td>{{ $b->AccountNumberSubID }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                                
                        </tbody>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
