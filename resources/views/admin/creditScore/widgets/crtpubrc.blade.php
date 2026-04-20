<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">
                    COURT PUBLIC RECORD DATA BAND
                </div>
            </div>
            <div class="card-body">
                <div class="deleted-table table-responsive">
                    <div class="text-center">
                    </div>
                    <table id="delete-datatables" class="table table-search text-nowrap mt-4 mb-4">
                        <thead>
                            <tr>
                                <th>CaseTypeCd</th>
                                <th>CourtCaseNumber</th>
                                <th>CaseStatusCd</th>
                                <th>CaseOpenDate</th>
                                <th>CaseCloseDate</th>
                                <th>CaseStatusDate</th>
                                <th>TrusteeNumber</th>
                                <th>DateStarted</th>
                                <th>TrusteeGivenNames</th>
                                <th>TrusteeBusinessName</th>
                                <th>TrusteeBusinessAddress</th>
                                <th>TrusteeContactName</th>
                                <th>TrusteeContactPhone</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($summary->CRTPUBRC))
                                @foreach ($summary->CRTPUBRC as $crtpubrcItem)
                                    <tr>
                                        <td>@php
                                            $caseTypeNames = [
                                                1 => 'Bankruptcy, Involuntary',
                                                2 => 'Bankruptcy, Voluntary',
                                                3 => 'Insolvency',
                                                20 => 'Bankruptcy: Admin Order (Part XI)',
                                                23 => 'Personal Insolvency Agreement',
                                                24 => 'Agreement (Part IX)',
                                                25 => 'Agreement (Part X)',
                                                // Add more as needed
                                            ];
                                        @endphp
                                            {{ $caseTypeNames[$crtpubrcItem->CaseTypeCd] ?? $crtpubrcItem->CaseTypeCd }}
                                        </td>
                                        <td>{{ $crtpubrcItem->CourtCaseNumber }}</td>
                                        @php
                                            $caseStatusNames = [
                                                3 => 'Closed',
                                                5 => 'Closed, Bankruptcy Discharged',
                                                7 => 'Closed, Dismissal',
                                                8 => 'Settled',
                                                11 => 'Open, Pending',
                                                12 => 'Annulment',
                                                13 => 'Early discharge',
                                                14 => 'Objection',
                                                15 => 'Automatic discharge',
                                                16 => 'Active',
                                                17 => 'Discharged',
                                                18 => 'Deleted',
                                                19 => 'Paid',
                                                20 => 'Discontinued',
                                                21 => 'Set Aside',
                                                22 => 'Accepted',
                                                23 => 'Terminated',
                                                24 => 'Rejected',
                                                25 => 'Withdrawn',
                                                26 => 'Cancelled',
                                            ];
                                        @endphp
                                        <td>

                                            {{ $caseStatusNames[$crtpubrcItem->CaseStatusCd] ?? $crtpubrcItem->CaseStatusCd }}
                                        </td>
                                        <td>{{ $crtpubrcItem->CaseOpenDate }}</td>
                                        <td>{{ $crtpubrcItem->CaseCloseDate }}</td>
                                        <td>{{ $crtpubrcItem->CaseStatusDate }}</td>
                                        <td>{{ $crtpubrcItem->BTRUSTEE->TrusteeNumber }}</td>
                                        <td>{{ $crtpubrcItem->BTRUSTEE->DateStarted }}</td>
                                        <td>{{ $crtpubrcItem->BTRUSTEE->TrusteeGivenNames }}</td>
                                        <td>{{ $crtpubrcItem->BTRUSTEE->TrusteeBusinessName }}</td>
                                        <td>{{ $crtpubrcItem->BTRUSTEE->TrusteeBusinessAddress }}</td>
                                        <td>{{ $crtpubrcItem->BTRUSTEE->TrusteeContactName }}</td>
                                        <td>{{ $crtpubrcItem->BTRUSTEE->TrusteeContactPhone }}</td>
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
