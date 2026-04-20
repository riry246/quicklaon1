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
                                <th>CaseOpenDate</th>
                                <th>CourtCaseNumber</th>
                                <th>CaseCourtName</th>
                                <th>CaseCourtTypeCd</th>
                                <th>CaseTypeCd</th>
                                <th>CaseTypeDtlCd</th>
                                <th>CaseStatusCd</th>
                                <th>CaseStatusDate</th>
                                <th>CasePlaintiffName</th>
                                <th>CaseLiabilityAmount</th>
                            </tr>
                        </thead>
                        <tbody>
                         @if (isset($summary->CRTPUBJG))
                            @foreach ($summary->CRTPUBJG as $crtpubjgItem)
                                <tr>
                                    <td>{{ $crtpubjgItem->CaseOpenDate }}</td>
                                    <td>{{ $crtpubjgItem->CourtCaseNumber }}</td>
                                    <td>{{ $crtpubjgItem->CaseCourtName }}</td>
                                    <td>@php
                                        $courtTypeNames = [
                                            1 => 'Bankruptcy Court',
                                            5 => 'Small Claims Court',
                                            8 => 'Magistrates\' Court',
                                            9 => 'County Court',
                                            10 => 'Supreme Court',
                                            11 => 'District Court',
                                            16 => 'Local Court',
                                            // Add more as needed
                                        ];
                                    @endphp


                                        {{ $courtTypeNames[$crtpubjgItem->CaseCourtTypeCd] ?? 'Unknown Court Type' }}
                                    </td>
                                    <td>
                                        @if ($crtpubjgItem->CaseTypeCd == 4)
                                            Judgment
                                        @else
                                            {{ $crtpubjgItem->CaseTypeCd }}
                                        @endif
                                    </td>
                                    <td>@php
                                        $caseTypeDtlNames = [
                                            7 => 'Judgment',
                                            8 => 'Summons',
                                            9 => 'Writ',
                                            // Add more as needed
                                        ];
                                    @endphp
                                        {{ $caseTypeDtlNames[$crtpubjgItem->CaseTypeDtlCd] ?? $caseTypeDtlNames[$crtpubjgItem->CaseTypeDtlCd] }}
                                    </td>
                                    <td>@php
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
                                            27 => 'Lapsed',
                                            // Add more as needed
                                        ];
                                    @endphp
                                        {{ $caseStatusNames[$crtpubjgItem->CaseStatusCd] ?? $crtpubjgItem->CaseStatusCd }}
                                    </td>
                                    <td>{{ $crtpubjgItem->CaseStatusDate }}</td>
                                    <td>{{ $crtpubjgItem->CasePlaintiffName }}</td>
                                    <td>{{ $crtpubjgItem->CaseLiabilityAmount }}</td>
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
