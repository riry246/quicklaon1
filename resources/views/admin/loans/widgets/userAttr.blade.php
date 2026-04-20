<div class="row">




    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">
                    Other Details
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-3">
                        <ul class="nav nav-tabs flex-column nav-style-4" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" role="tab" aria-current="page"
                                    href="#reason" aria-selected="true">Loan
                                    Details</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page"
                                    href="#income" aria-selected="true">Income</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " data-bs-toggle="tab" role="tab" aria-current="page"
                                    href="#employer-details" aria-selected="true">Employer Details</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page"
                                    href="#question" aria-selected="true">Questions</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page"
                                    href="#terms" aria-selected="true">Terms And Conditons</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page"
                                    href="#expenses" aria-selected="true">Expenses</a>
                            </li>

                        </ul>
                    </div>
                    <div class="col-xl-9">
                        <div class="tab-content">
                            <div class="tab-pane show active text-muted" id="reason" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <p class="fs-15 mb-2 me-4 fw-semibold">Loan Details:</p>
                                        <p class="fs-15 mb-2 me-4 fw-normal">
                                            {{ ucfirst($result['reason_for_loan'] ?? '') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane text-muted" id="income" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-4">
                                        <p class="fs-15 mb-2 me-4 fw-semibold">Income Type:</p>
                                        <p class="fs-15 mb-2 me-4 fw-normal">
                                            {{ ucfirst($result['income_type'] ?? '') }}</p>
                                    </div>
                                    @if (!empty($result['income_type']))
                                        @if (in_array($result['income_type'], ['wages', 'wages-centerlink']))
                                            <div class="col-xl-4">
                                                <p class="fs-15 mb-2 me-4 fw-semibold">Employment Type:
                                                </p>
                                                <p class="fs-15 mb-2 me-4 fw-normal">
                                                    {{ ucfirst($result['employment_type'] ?? '') }}</p>
                                            </div>
                                        @endif
                                    @endif

                                    <div class="col-xl-4">
                                        <p class="fs-15 mb-2 me-4 fw-semibold">Pay Cycle:</p>
                                        <p class="fs-15 mb-2 me-4 fw-normal">
                                            {{ ucfirst($result['pay_cycle'] ?? '') }}</p>
                                    </div>
                                    <div class="col-xl-4">
                                        <p class="fs-15 mb-2 me-4 fw-semibold">Pay Day:</p>
                                        <p class="fs-15 mb-2 me-4 fw-normal">
                                            {{ ucfirst($result['pay_day'] ?? '') }}</p>
                                    </div>
                                    <div class="col-xl-4">
                                        <p class="fs-15 mb-2 me-4 fw-semibold">Pay Time:</p>
                                        <p class="fs-15 mb-2 me-4 fw-normal">
                                            {{ ucfirst($result['pay_time'] ?? '') }}</p>
                                    </div>
                                    @if (!empty($result['income_type']))
                                        @if (in_array($result['income_type'], ['wages', 'wages-centerlink']))
                                            <div class="col-xl-4">
                                                <p class="fs-15 mb-2 me-4 fw-semibold">Wages after tax:
                                                </p>
                                                <p class="fs-15 mb-2 me-4 fw-normal">
                                                    $ {{ $result['wages_after_tax'] ?? '' }}</p>
                                            </div>
                                        @endif
                                    @endif
                                    @if (!empty($result['income_type']))
                                        @if (in_array($result['income_type'], ['centerlink', 'wages-centerlink']))
                                            <div class="col-xl-4">
                                                <p class="fs-15 mb-2 me-4 fw-semibold">Centerlink after
                                                    tax:
                                                </p>
                                                <p class="fs-15 mb-2 me-4 fw-normal">
                                                    $ {{ $result['centerlink_after_tax'] ?? '' }}</p>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="tab-pane text-muted" id="employer-details" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-4">
                                        <p class="fs-15 mb-2 me-4 fw-semibold">Employment Type:
                                        </p>
                                        <p class="fs-15 mb-2 me-4 fw-normal">
                                            {{ ucfirst($result['employment_type'] ?? '') }}</p>
                                    </div>
                                    <div class="col-xl-4">
                                        <p class="fs-15 mb-2 me-4 fw-semibold">Company Name:</p>
                                        <p class="fs-15 mb-2 me-4 fw-normal">
                                            {{ $result['company_name'] ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-xl-4">
                                        <p class="fs-15 mb-2 me-4 fw-semibold">Contact Person:
                                        </p>
                                        <p class="fs-15 mb-2 me-4 fw-normal">
                                            {{ $result['contact_person'] ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-xl-4">
                                        <p class="fs-15 mb-2 me-4 fw-semibold">Contact Person
                                            Email:</p>
                                        <p class="fs-15 mb-2 me-4 fw-normal">
                                            {{ $result['contact_person_email'] ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-xl-4">
                                        <p class="fs-15 mb-2 me-4 fw-semibold">Contact Person
                                            Mobile:</p>
                                        <p class="fs-15 mb-2 me-4 fw-normal">
                                            {{ $result['contact_person_mobile_number'] ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane text-muted" id="question" role="tabpanel">
                                <div class="row">
                                    @if (isset($result['question']))
                                        @foreach ($result['question'] as $k => $v)
                                            <div class="col-xl-12">
                                                <p class="fs-15 mb-2 me-4 fw-semibold">
                                                    {{ ucwords(str_replace(['question_', '-'], [' ', ' '], $k)) }} ?
                                                </p>
                                                <p class="fs-15 mb-2 me-4 fw-normal">
                                                    {{ $v }}
                                                </p>
                                                @if ($k == 'question_do-you-have-any-other-outstanding-loans-or-financial-commitments')
                                                    <div class="row">
                                                        <div class="col-xl-4">
                                                            <p class="fs-15 mb-2 me-4 fw-semibold">Institute Name:</p>
                                                            <p class="fs-15 mb-2 me-4 fw-normal">
                                                                {{ $result['name_of_institution'] ?? 'N/A' }}</p>
                                                        </div>
                                                        <div class="col-xl-4">
                                                            <p class="fs-15 mb-2 me-4 fw-semibold">Amount Owned:</p>
                                                            <p class="fs-15 mb-2 me-4 fw-normal">
                                                                $ {{ $result['amount_owned'] ?? 'N/A' }}</p>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="tab-pane text-muted" id="terms" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-4">
                                        <p class="fs-15 mb-2 me-4 fw-semibold">Refer Person Name:
                                        </p>
                                        <p class="fs-15 mb-2 me-4 fw-normal">
                                            {{ $result['ref_person_name'] ?? '' }}</p>
                                    </div>
                                    <div class="col-xl-4">
                                        <p class="fs-15 mb-2 me-4 fw-semibold">Contact Number:</p>
                                        <p class="fs-15 mb-2 me-4 fw-normal">
                                            {{ $result['ref_contract_number'] ?? '' }}</p>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-xl-4">
                                        <p class="fs-15 mb-2 me-4 fw-semibold">Agreed Terms and Condition:
                                        </p>
                                        <p class="fs-15 mb-2 me-4 fw-normal">
                                            @if (isset($result['agreed_terms']))
                                                Yes
                                            @endif
                                    </div>
                                    <div class="col-xl-4">
                                        <p class="fs-15 mb-2 me-4 fw-semibold">Agreed Privacy Policy:
                                        </p>
                                        <p class="fs-15 mb-2 me-4 fw-normal">
                                            @if (isset($result['agreed_privacy_policy']))
                                                Yes
                                            @endif
                                    </div>
                                    <div class="col-xl-4">
                                        <p class="fs-15 mb-2 me-4 fw-semibold">Agreed Credit Guide:
                                        </p>
                                        <p class="fs-15 mb-2 me-4 fw-normal">
                                            @if (isset($result['agreed_credit_guide']))
                                                Yes
                                            @endif
                                    </div>
                                    <div class="col-xl-12">
                                        <p class="fs-15 mb-2 me-4 fw-semibold">Agreed Direct Debit from Monoova:
                                        </p>
                                        <p class="fs-15 mb-2 me-4 fw-normal">
                                            @if (isset($result['agreed_direct_debit_from_monoova']))
                                                Yes
                                            @endif
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane text-muted" id="expenses" role="tabpanel">
                                <div class="row">
                                    @if (isset($result['expenses']))
                                        @foreach ($result['expenses'] as $k => $v)
                                            <div class="col-xl-4">
                                                <p class="fs-15 mb-2 me-4 fw-semibold">
                                                    {{ ucwords(str_replace(['expenses_', '-'], [' ', ' '], $k)) }}</p>
                                                <p class="fs-15 mb-2 me-4 fw-normal">
                                                    $ {{ $v }}</p>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin.loans.widgets.idVerification')
