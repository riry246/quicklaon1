<div class="card custom-card overflow-hidden">
    <div class="card-body p-0">
        <div class="d-sm-flex align-items-top p-4 border-bottom-0 main-profile-cover">
            <div class="flex-fill main-profile-info">
                <div class="d-flex align-items-center justify-content-between">
                    <h6 class="fw-semibold mb-1 text-fixed-white">
                        {{ trim(
                            ucwords($summary->PERSONPF->PERSONPFID->Title ?? '') .
                                ' ' .
                                ucfirst($summary->PERSONPF->PERSONPFID->FirstGivenName ?? '') .
                                ' ' .
                                ucfirst($summary->PERSONPF->PERSONPFID->MiddleName ?? '') .
                                ' ' .
                                ucfirst($summary->PERSONPF->PERSONPFID->FamilyName ?? ''),
                        ) }}
                        <br />
                        {{ isset($summary->PERSONPF->PERSONPFID->ALIASNAM[0]->AliasName) ? '(' . ucfirst($summary->PERSONPF->PERSONPFID->ALIASNAM[0]->AliasName) . ')' : '' }}

                    </h6>
                </div>
                <p class="mb-1 text-muted text-fixed-white op-7">
                    @if ($summary->PERSONPF->PERSONPFID->BanInd == 'N')
                        Consumer is not in a Ban period.
                    @else
                        Consumer is in a Ban period.
                    @endif
                </p>
                <p class="fs-12 text-fixed-white mb-4 op-5">
                    <span class="me-3"><i class="ri-cake-2-line me-1 align-middle"></i>
                        @php
                            $day = $summary->PERSONPF->PERSONPFID->BirthDay ?? '';
                            $month = $summary->PERSONPF->PERSONPFID->BirthMonth ?? '';
                            $year = $summary->PERSONPF->PERSONPFID->BirthYear ?? '';

                            // Concatenate day, month, and year
                            $dateString = $day . '-' . $month . '-' . $year;

                            // Use Carbon to format the date
                            $formattedDate = \Carbon\Carbon::createFromFormat('d-m-Y', $dateString)->format('d M,Y');
                        @endphp
                        {{ $formattedDate }}
                    </span>
                    <span>
                        @php
                            $genderIcon = [
                                1 => 'ri-women-line',
                                2 => 'ri-men-line',
                                // Add more icons if needed
                            ];

                            $gender = $summary->PERSONPF->PERSONPFID->Gender ?? 0;
                        @endphp

                        <i class="{{ $genderIcon[$gender] ?? 'ri-genderless-line' }} me-1 align-middle"></i>
                        {{ $gender == 1 ? 'Female' : ($gender == 2 ? 'Male' : 'Unknown/Unspecified') }}
                    </span>
                </p>
                <div class="d-flex mb-0">
                    <div class="me-4">
                        <p class="fw-bold fs-20 text-fixed-white text-shadow mb-0">
                            {{ $summary->MODEL0001[0]->ScoreValue ?? 'N/A' }}
                        </p>
                        <p class="mb-0 fs-11 op-5 text-fixed-white">Score Value</p>
                    </div>
                    <div class="me-4">
                        <p class="fw-bold fs-20 text-fixed-white text-shadow mb-0">
                            {{ $summary->MODEL0001[0]->ScoreCardNum ?? 'N/A' }}
                        </p>
                        <p class="mb-0 fs-11 op-5 text-fixed-white">Score Card Num</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-4 border-bottom border-block-end-dashed">
            <div class="d-flex justify-content-between align-items-center ">
                <p class="fs-18 fw-semibold"><span class="fs-11 text-muted op-7">Reference
                        Number:</span><br />#{{ $summary->BURPTHDR->BurRptNum ?? 'N/A' }}</p>
                <div>
                    <span class="badge bg-success-transparent">{{ $summary->BURPTHDR->BurRptDate ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
        <div class="p-4 border-bottom border-block-end-dashed">
            <div class="mb-0">
                <p class="fs-15 mb-2 fw-semibold">ID Type :</p>
                <div class="mb-0">
                    <div class="ms-sm ms-0 mt-sm-0 mt-1 fw-semibold flex-fill">
                        <p class="mb-0 lh-1">
                            {{ $summary->PERSONPF->PERSONPFID->IDENTCRD[0]->IdNumber ?? 'N/A' }}
                        </p>
                        <span class="fs-11 text-muted op-7">Driver's License Number:</span>
                    </div>
                </div>
            </div>
        </div>
        @if (isset($summary->PERSONPF->EMPLOYER))
            <div class="p-4 border-bottom border-block-end-dashed">
                <div class="mb-0">
                    <p class="fs-15 mb-2 fw-semibold">Employer Name:</p>
                    <div class="ms-sm ms-0 mt-sm-0 mt-1 fw-semibold flex-fill">
                        <p class="mb-0 lh-1">{{ $summary->PERSONPF->EMPLOYER->EmployerName ?? 'N/A' }}</p>
                        <span class="fs-11 text-muted op-7">{{ $summary->PERSONPF->EMPLOYER->EmpDate ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
