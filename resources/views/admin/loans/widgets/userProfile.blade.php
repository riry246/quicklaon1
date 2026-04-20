<div class="card custom-card overflow-hidden">

    <div class="card-header justify-content-between main-profile-cover main-profile-cover-warning badDebtIndicator"
        style="display: none">
        <div class="card-title ">
            <i class="ri-alarm-warning-line align-middle d-inline-block me-2"></i>
            <span class="py-0">Bad Debt Account</span>
        </div>
    </div>
    @if ($loanapplication->in_default == 1)
        <div class="card-header justify-content-between main-profile-cover main-profile-cover-warning">
            <div class="card-title ">
                <i class="ri-alarm-warning-line align-middle d-inline-block me-2"></i>
                <span class="py-0">In Default Account</span>
            </div>
        </div>
    @endif
    @if ($loanapplication->excessive_outstanding_flag == 1)
        <div class="card-header justify-content-between main-profile-cover main-profile-cover-warning">
            <div class="card-title ">
                <i class="ri-alarm-warning-line align-middle d-inline-block me-2"></i>
                <span class="py-0">Excessive Outstanding Application</span>
            </div>
        </div>
    @endif

    <div class="card-body p-0">
        @if ($loan_helper->checkRiskFactor($loanapplication->user_id) == 'low')
            <div class="d-sm-flex align-items-top p-4 border-bottom-0 main-profile-cover main-profile-cover-success">
            @elseif($loan_helper->checkRiskFactor($loanapplication->user_id) == 'medium')
                <div
                    class="d-sm-flex align-items-top p-4 border-bottom-0 main-profile-cover main-profile-cover-warning">
                @elseif ($loanapplication->user->risk_flag)
                    <div
                        class="d-sm-flex align-items-top p-4 border-bottom-0 main-profile-cover main-profile-cover-danger">
                    @else
                        <div class="d-sm-flex align-items-top p-4 border-bottom-0 main-profile-cover">
        @endif
        <div class="flex-fill main-profile-info">
            <div class="d-flex align-items-center justify-content-between">
                <h6 class="fw-semibold mb-1 text-fixed-white">{{ $result['title'] ?? 'N/A' }}
                    {{ $user['first_name'] }}
                    {{ $user['middle_name'] }}
                    {{ $user['last_name'] }}</h6>

            </div>
            @if ($loanapplication->user->risk_flag)
                <p class="fs-12 text-fixed-white mb-4 "> <span class="me-3 fw-bold fs-13">
                        <i class="ri-alarm-warning-line me-1 align-middle"></i>
                        @if ($loan_helper->checkRiskFactor($loanapplication->user_id) == 'low')
                            This user has a clear history of successful loan repayments with us.
                        @elseif ($loanapplication->user->risk_flag == 1)
                            Alert: Possible Duplicate User Information Detected.
                        @elseif ($loanapplication->user->risk_flag == 2)
                            Warning: Attempt to Verify Previously Submitted ID.
                        @endif
                    </span> </p>
            @endif
            <div class="d-flex mb-0">
                <div class="me-4">
                    <p class="fw-bold fs-20 text-fixed-white text-shadow mb-0"><a target="_blank"
                            href="{{ route('customer.view', $user['id']) }}">{{ count($noofloans) }}</a></p>
                    <p class="mb-0 fs-11  text-fixed-white">Loans</p>
                </div>
                <div class="me-4">
                    <p class="fw-bold fs-20 text-fixed-white text-shadow mb-0">{{ $score ?? 0 }}</p>
                    <p class="mb-0 fs-11  text-fixed-white">CF Score</p>
                </div>
                <div class="me-4">
                    <p class="fw-bold fs-20 text-fixed-white text-shadow mb-0">{{ count($noofdishournedpayment) }}</p>
                    <p class="mb-0 fs-11  text-fixed-white">Dishourned Payment</p>
                </div>
            </div>
        </div>
    </div>

    <div class="p-4 border-bottom">
        <p class="fs-15 mb-2 me-4 fw-semibold">Contact Information :</p>
        <div class="text-muted">
            <p class="mb-2"> <span class="avatar avatar-sm avatar-rounded me-2 bg-light text-muted"> <i
                        class="ri-mail-line align-middle fs-14"></i> </span> {{ $user['email'] ?? 'N/A' }}
                @if ($user['email_verified_at'])
                    <i class='bx bxs-check-circle text-success'></i>
                @else
                    <i class='bx bxs-error-alt text-warning'></i>
                @endif
            </p>
            <p class="mb-2"> <span class="avatar avatar-sm avatar-rounded me-2 bg-light text-muted"> <i
                        class="ri-phone-line align-middle fs-14"></i> </span>{{ $user['mobile'] ?? 'N/A' }}
                @if ($user['mobile_verified_at'])
                    <i class='bx bxs-check-circle text-success'></i>
                @else
                    <i class='bx bxs-error-alt text-warning'></i>
                @endif
            </p>
            <p class="mb-2"> <span class="avatar avatar-sm avatar-rounded me-2 bg-light text-muted"> <i
                        class="ri-cake-2-line align-middle fs-14"></i>
                </span>{{ $loan_helper->formateDateDynamic($user['dob'], 'd M, Y') ?? 'N/A' }}
            </p>
            <p class="mb-0"> <span class="avatar avatar-sm avatar-rounded me-2 bg-light text-muted"> <i
                        class="ri-map-pin-line align-middle fs-14"></i> </span> {{ $result['address'] ?? 'N/A' }}
            </p>
        </div>
    </div>
    <div class="p-4 border-bottom">
        <div class="col-xl-12">
            <div class="row justify-content-between">
                <div>
                    <p>Temporarily Customer Login Url :</p>
                </div>
                <div class="btn-list text-center">
                    <a target="_blank"
                        href="{{ 'https://app.cashfaster.com.au/users/login/' . $user->temp_login_token }}"
                        class="btn btn-icon btn-outline-warning rounded-pill btn-wave waves-effect waves-light"
                        data-bs-toggle="tooltip" data-bs-custom-class="tooltip-primary" data-bs-placement="top"
                        data-bs-original-title="Login as  {{ $user['first_name'] }}"><i
                            class="ri-login-circle-line align-middle d-inline-block"></i></a>
                    <a target="_blank"
                        href="{{ 'https://app.cashfaster.com.au/users/login/' . $user->temp_login_token }}"
                        class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"
                        data-bs-toggle="tooltip" data-bs-custom-class="tooltip-primary" data-bs-placement="top"
                        data-bs-original-title="Copy Url" id="copyButton"
                        data-url="{{ 'https://app.cashfaster.com.au/users/login/' . $user->temp_login_token }}">
                        <i class="ri-clipboard-line align-middle d-inline-block"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="p-4 border-bottom">
        <div class="col-xl-12">
            <input type="hidden" value="{{ $loanapplication->id }}" name="id" />
            <div class="custom-toggle-switch d-flex align-items-center" id="badDebtIndicator">
                <input id="toggleswitchDark" name="isBadDebt" type="checkbox"
                    @if ($loanapplication->is_bad_debt) checked @endif>
                <label for="toggleswitchDark" class="label-success"></label><span class="ms-3 text-change-span">Marked
                    as Bad
                    debt</span>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('copyButton').addEventListener('click', function(event) {
            event.preventDefault();
            var url = this.getAttribute('data-url');

            // Create a temporary input element
            var tempInput = document.createElement('input');
            tempInput.value = url;
            document.body.appendChild(tempInput);

            // Select the text field
            tempInput.select();
            tempInput.setSelectionRange(0, 99999); // For mobile devices

            // Copy the text inside the text field
            document.execCommand('copy');

            // Remove the temporary input element
            document.body.removeChild(tempInput);

            // Optional: Display a message or tooltip to indicate the URL has been copied
            alert('URL copied to clipboard: ' + url);
        });
    </script>

</div>
</div>
