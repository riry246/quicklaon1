@if (isset($illionBankAccount[0]))
    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">Compliance Checker</div>
            </div>
            <div class="card-body">
                @php

                    $givenName = $user['first_name'] . ' ' . $user['middle_name'] . ' ' . $user['last_name'];
                    $accountName = $illionBankAccount[0]->account_holder;

                    $matchPercent = $dashboard_helper->calculateMatchPercentage($givenName, $accountName);

                    $givenAddress = $result['address'];
                    $accountAddress = json_decode($illionCustomerInfo->address);
                    $accountAddress = $accountAddress->text ?? 'N/A';
                    $addressmatchPercent = $dashboard_helper->calculateMatchPercentage($accountAddress, $givenAddress);

                    $timelineItems = [
                        [
                            'icon' =>
                                $matchPercent > 80
                                    ? 'bx bxs-check-circle text-success'
                                    : 'bx bxs-error-alt text-warning',
                            'title' => 'Customer Name',
                            'status' => $matchPercent . '% matched',
                            'given' => $givenName,
                            'account' => $accountName,
                            'statusClass' =>
                                $matchPercent > 80 ? 'badge bg-success-transparent' : 'badge bg-warning-transparent',
                        ],
                        [
                            'icon' =>
                                $addressmatchPercent > 80
                                    ? 'bx bxs-check-circle text-success'
                                    : 'bx bxs-error-alt text-warning',
                            'title' => 'Customer Address',
                            'status' => $addressmatchPercent . '% matched',
                            'given' => $givenAddress,
                            'account' => $accountAddress,
                            'statusClass' =>
                                $addressmatchPercent > 80
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
                                        <p class="mb-1 text-truncate timeline-widget-content text-wrap">
                                            {{ $item['title'] }}
                                        </p>
                                        <p class="fs-14 mb-1 text-muted">
                                            <span
                                                class="{{ $item['statusClass'] }}">{{ ucfirst($item['status']) }}</span>
                                        </p>
                                        <p class="fs-14 mb-1 text-muted">
                                            <span><b>Given:</b> {{ $item['given'] }}</span>
                                        </p>
                                        <p class="fs-14 mb-1 text-muted">
                                            <span><b>Account:</b> {{ $item['account'] ?? 'N/A' }}</span>
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
@endif
