@if ($loanapplication->status !== 'active' && $loanapplication->status !== 'completed')
    <div class="row">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">Loan Action</div>
            </div>
            <div class="card-body">
                <div class="btn-list">
                    <div class="d-grid gap-2 mb-4">
                        @if ($loanapplication->status == 'pending')
                            <a href="{{ route('loan.status', ['id' => $loanapplication['id'], 'status' => 'processing']) }}"
                                class="btn btn-primary btn-wave" type="button">Proceed with Application</a>

                            <button class="btn btn-danger btn-wave" type="button" data-bs-toggle="modal"
                                data-bs-target="#loan-decline">Decline Application</button>
                        @elseif($loanapplication->status == 'processing')
                            @if (count($contract) > 0 && $loanapplication->customer_confirmation == 1)
                                <button class="btn btn-primary btn-wave" type="button" data-bs-toggle="modal"
                                    data-bs-target="#loan-approve">Approve Application</button>
                            @else
                                <button class="btn btn-primary btn-wave" type="button" data-bs-toggle="modal"
                                    data-bs-target="#pre-approval">Pre Approval</button>
                            @endif
                            <button class="btn btn-danger btn-wave" type="button" data-bs-toggle="modal"
                                data-bs-target="#loan-decline">Decline Application</button>
                        @elseif($loanapplication->status == 'declined')
                            <p class="fs-15 mb-2 me-4 fw-semibold">Rejection Reason:</p>
                            <p class="fs-15 mb-2 me-4 fw-normal">{{ $loanapplication->rejection_reason }}</p>
                        @else
                            <button class="btn btn-danger btn-wave" type="button" data-bs-toggle="modal"
                                data-bs-target="#loan-decline">Decline Application</button>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@elseif ($loanapplication->status == 'active')
    <div class="row">
        <div class="card custom-card">
            <div class="card-header">
                <div class="card-title">Loan Action</div>
            </div>
            <div class="card-body">
                <div class="btn-list">
                    <div class="d-grid gap-2 mb-4">
                        <button class="btn btn-success btn-wave" type="button" data-bs-toggle="modal"
                            data-bs-target="#loan-complete">Settle Application</button>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="loan-complete" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title">Settle Loan Application</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-4">
                            <div class="row gy-3">
                                <div class="col-xl-12" style="width: 500px">
                                    <label>Are you sure? You want to settle this loan application. This action is
                                        irreversible.</label>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="{{ route('loan.status', ['id' => $loanapplication['id'], 'status' => 'completed']) }}"
                                class="btn btn-success btn-wave" type="button">Yes</a>
                            <button type="button" class="btn btn-danger btn-wave" data-bs-dismiss="modal"
                                aria-label="Close">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endif
<!--
<div class="row">
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">Loan Action (For Test will be removed later)</div>
        </div>
        <div class="card-body">
            <div class="btn-list">
                <div class="d-grid gap-2 mb-4">
                    <a href="{{ route('loan.status', ['id' => $loanapplication['id'], 'status' => 'processing']) }}"
                        class="btn btn-primary btn-wave" type="button">Change to processing</a>

                    <a href="{{ route('loan.status', ['id' => $loanapplication['id'], 'status' => 'incomplete']) }}"
                        class="btn btn-primary btn-wave" type="button">Change to incomplete</a>

                    <a href="{{ route('loan.status', ['id' => $loanapplication['id'], 'status' => 'completed']) }}"
                        class="btn btn-primary btn-wave" type="button">Change to complete</a>

                    <a href="{{ route('loan.status', ['id' => $loanapplication['id'], 'status' => 'pending']) }}"
                        class="btn btn-primary btn-wave" type="button">Change to pending</a>
                </div>
            </div>
        </div>
    </div>
</div> -->
<!-- Start:: Approve loan -->
<div class="modal fade" id="loan-approve" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('loan.approved', $loanapplication['id']) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Approve Loan</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <div class="alert alert-solid-success alert-dismissible fs-15 fade show mb-4 authcodesent"
                        style="display:none">
                        <ul class="list-unstyled">
                            <li> Payment Authorization Code sent to registered number </li>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i
                                class="bi bi-x"></i></button>
                    </div>
                    <div class="row gy-3">
                        <div class="col-xl-12" style="width: 500px">
                            <label>Authorize Payment</label>
                            <input class="form-control mt-2" name="otp" required maxlength="4" />
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" id="sendCode">Generate Transaction Code</button>
                    <button class="btn btn-primary">Approve & Dispatch Fund</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Start:: Decline loan -->
<div class="modal fade" id="loan-decline" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('loan.decline', $loanapplication['id']) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Decline Loan</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <div class="row gy-3">
                        <div class="col-xl-12" style="width: 500px">
                            <label>Rejection Reason</label>
                            <select class="form-control mt-2" name="rejection_reason" id="choices-multiple-groups">
                                <option value="">Choose a reason</option>

                                <optgroup label="Declined">
                                    <option value="Bad Credit File">Bad Credit File</option>
                                    <option value="Fraud (potential)">Fraud (potential)</option>
                                </optgroup>

                                <optgroup label="Government Policy">
                                    <option value="Cannot speak English">Cannot speak English</option>
                                    <option value="Cannot supply documents">Cannot supply documents</option>
                                    <option value="Centrelink">Centrelink</option>
                                    <option value="SACCS">SACCS</option>
                                    <option value="Inconsistent Income">Inconsistent Income</option>
                                </optgroup>

                                <optgroup label="Lending Policy">
                                    <option value="Age">Age</option>
                                    <option value="Dishonours">Dishonours</option>
                                    <option value="Gambling / Gaming">Gambling / Gaming</option>
                                    <option value="Low Income">Low Income</option>
                                    <option value="No Security">No Security</option>
                                    <option value="Residency">Residency</option>
                                    <option value="Unemployed">Unemployed</option>
                                </optgroup>

                                <option value="Multiple Enquiries">Multiple Enquiries</option>
                                <option value="Serviceability">Serviceability</option>
                                <optgroup label="Did not wish to proceed">
                                    <option value="Funded elsewhere">Funded elsewhere</option>
                                    <option value="No longer requires">No longer requires</option>
                                </optgroup>
                            </select>



                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger">Decline</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="pre-approval" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('loan.pre.approved', $loanapplication['id']) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Pre-approval</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <div class="row gy-3">
                        <div class="col-xl-12" style="width: 500px">
                            <label>Approved Amount</label>
                            <input type="number" class="form-control mt-2" name="amount"
                                value="{{ $loanapplication['amount'] }}" />
                        </div>
                    </div>
                    <div class="row gy-3 mt-2">
                        <div class="col-xl-12" style="width: 500px">
                            <label>Payment Frequency</label>
                            <select class="form-control mt-2" name="frequency">
                                @foreach (['weekly', 'fortnightly'] as $value)
                                    <option value="{{ $value }}"
                                        {{ $loanapplication->frequency == $value ? 'selected' : '' }}>
                                        {{ ucfirst($value) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row gy-3 mt-2">
                        <div class="col-xl-12" style="width: 500px">
                            <label>No. of repayments</label>
                            <select class="form-control mt-2" name="duration">
                                @foreach ([4,6,8,12] as $value)
                                    <option value="{{ $value }}"
                                        {{ $loanapplication->duration == $value ? 'selected' : '' }}>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row gy-3 mt-2">
                        <div class="col-xl-12" style="width: 500px">
                            <label>First Payment Date</label>
                            <div class="input-group mt-2">
                                <div class="input-group-text text-muted"> <i class="ri-calendar-line"></i> </div>
                                <input type="text" name="first_repayment_date" class="form-control"
                                    value="{{ $loanapplication->first_repayment_date }}" id="date"
                                    placeholder="Choose date">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Send</button>
                </div>
            </div>
        </form>
    </div>
</div>
