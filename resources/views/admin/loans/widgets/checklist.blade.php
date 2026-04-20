<div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12">
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">Checklist</div>
        </div>
        <div class="card-body">
            @php
                if (isset($result['are_you_a_permanent_resident'])) {
                    if ($result['are_you_a_permanent_resident'] == 1) {
                        $pr = 'Yes';
                    } elseif ($result['are_you_a_permanent_resident'] == 0) {
                        $pr = 'No';
                    } else {
                        $pr = 'Yes';
                    }
                } else {
                    $pr = 'No';
                }

                $timelineItems = [
                    [
                        'icon' => $user['email_verified_at']
                            ? 'bx bxs-check-circle text-success'
                            : 'bx bxs-error-alt text-warning',
                        'title' => 'Email Address',
                        'status' => $user['email_verified_at'] ? 'Verified' : 'Not Verified',
                        'statusClass' => $user['email_verified_at']
                            ? 'badge bg-success-transparent'
                            : 'badge bg-warning-transparent',
                    ],
                    [
                        'icon' => $user['mobile_verified_at']
                            ? 'bx bxs-check-circle text-success'
                            : 'bx bxs-error-alt text-warning',
                        'title' => 'Mobile Number',
                        'status' => $user['mobile_verified_at'] ? 'Verified' : 'Not Verified',
                        'statusClass' => $user['mobile_verified_at']
                            ? 'badge bg-success-transparent'
                            : 'badge bg-warning-transparent',
                    ],
                    [
                        'icon' => $pr == 'Yes' ? 'bx bxs-check-circle text-success' : 'bx bxs-error-alt text-warning',
                        'title' => 'Permanent Resident',
                        'status' => $pr == 'Yes' ? 'Yes' : 'No',
                        'statusClass' => $pr == 'Yes' ? 'badge bg-success-transparent' : 'badge bg-warning-transparent',
                    ],
                    [
                        'icon' => $user['id_verified']
                            ? 'bx bxs-check-circle text-success'
                            : 'bx bxs-error-alt text-warning',
                        'title' => 'ID Verification',
                        'status' => $user['id_verified'] ? 'Verified' : 'Not Verified',
                        'statusClass' => $user['id_verified']
                            ? 'badge bg-success-transparent'
                            : 'badge bg-warning-transparent',
                    ],
                    [
                        'icon' => $loanapplication['customer_confirmation']
                            ? 'bx bxs-check-circle text-success'
                            : 'bx bxs-error-alt text-warning',
                        'title' => 'Approved Loan Amount',
                        'status' => $loanapplication['customer_confirmation'] ? 'Approved' : 'Not Approved Yet',
                        'statusClass' => $loanapplication['customer_confirmation']
                            ? 'badge bg-success-transparent'
                            : 'badge bg-warning-transparent',
                    ],
                    [
                        'icon' => $loanapplication->latestcontractStatus
                            ? 'bx bxs-check-circle text-success'
                            : 'bx bxs-error-alt text-warning',
                        'title' => 'Contract Signing',
                        'status' => $loanapplication->latestcontractStatus
                            ? $loanapplication->latestcontractStatus->status
                            : 'Not sent Yet',
                        'statusClass' => $loanapplication->latestcontractStatus
                            ? 'badge bg-success-transparent'
                            : 'badge bg-warning-transparent',
                    ],
                ];
            @endphp

            <ul class="list-unstyled timeline-widget mb-0 my-3">
                @foreach ($timelineItems as $item)
                    <li class="timeline-widget-list">
                        <div class="d-flex align-items-top">
                            <div class="me-5 text-center">
                                <span class="d-block fs-22 fw-semibold text-primary">
                                    <i class="{{ $item['icon'] }}"></i>
                                </span>
                            </div>
                            <div class="d-flex flex-wrap flex-fill align-items-top justify-content-between">
                                <div>
                                    <p class="mb-1 text-truncate timeline-widget-content text-wrap">{{ $item['title'] }}
                                    </p>
                                    <p class="fs-14 mb-1 text-muted">
                                        <span class="{{ $item['statusClass'] }}">{{ ucfirst($item['status']) }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
