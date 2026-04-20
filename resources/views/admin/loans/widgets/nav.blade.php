@php
    // Generate a random session code
    $randomSessionCode = bin2hex(random_bytes(16)); // generates a 32-character random string
@endphp

<nav class="navbar navbar-expand-lg bg-light mb-3">
    <div class="container-fluid">
        <div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent">
            <div class="card-title fw-bold fs-16">Application #{{ $loanapplication['id'] }}</div>
            <div class="p-2 text-center">
                <div class="btn-list">
                    <button class="btn btn-icon btn-outline-teal rounded-pill btn-wave waves-effect waves-light"
                        data-bs-toggle="modal" data-bs-target="#ticket" data-bs-custom-class="tooltip-primary"
                        data-bs-placement="top" data-bs-original-title="Create Ticket" title="Create Ticket"
                        aria-describedby="tooltip216440">
                        <i class="ri-customer-service-2-line"></i>
                    </button>
                    <a target='_blank' href="{{ route('user.edit', $user->id) }}"
                        class="btn btn-icon btn-outline-secondary rounded-pill btn-wave waves-effect waves-light"
                        data-bs-toggle="tooltip" data-bs-custom-class="tooltip-primary" data-bs-placement="top"
                        data-bs-original-title="Edit User" aria-describedby="tooltip216447">
                        <i class="ri-edit-line"></i> </a>

                    <a target='_blank'
                        href="{{ route('loan.statement', ['userid' => $user->basiq_user_id ?? $randomSessionCode, 'id' => $loanapplication->id]) }}"
                        class="btn btn-icon btn-outline-success rounded-pill btn-wave waves-effect waves-light"
                        data-bs-toggle="tooltip" data-bs-custom-class="tooltip-primary" data-bs-placement="top"
                        data-bs-original-title="View Loan Statement" aria-describedby="tooltip216447">
                        <i class="ri-file-chart-line"></i> </a>

                    <a target='_blank' href="{{ route('transaction.user.list', $user->id) }}"
                        class="btn btn-icon btn-outline-warning rounded-pill btn-wave waves-effect waves-light"
                        data-bs-toggle="tooltip" data-bs-custom-class="tooltip-primary" data-bs-placement="top"
                        data-bs-original-title="Transactions" aria-describedby="tooltip216447">
                        <i class="ri-article-line"></i>
                    </a>
                    <button class="btn btn-icon btn-outline-teal rounded-pill btn-wave waves-effect waves-light"
                        data-bs-toggle="modal" data-bs-target="#assign" data-bs-custom-class="tooltip-primary"
                        data-bs-placement="top" data-bs-original-title="Create Ticket" title="Assign Application"
                        aria-describedby="tooltip216440">
                        <i class="ri-send-plane-2-fill"></i>
                    </button>
                    @if ($user->temp_login_token)
                        <a target="_blank"
                            href="{{ 'https://app.cashfaster.com.au/users/login/' . $user->temp_login_token }}"
                            class="btn btn-icon btn-outline-warning rounded-pill btn-wave waves-effect waves-light"
                            data-bs-toggle="tooltip" data-bs-custom-class="tooltip-primary" data-bs-placement="top"
                            data-bs-original-title="Login as  {{ $user['first_name'] }}"><i
                                class="ri-login-circle-line align-middle d-inline-block"></i></a>
                    @endif
                </div>
            </div>

        </div>
    </div>
</nav>
<div class="modal modal-lg fade" id="ticket" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Create Ticket</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-4">
                <div class="col-xl-12">
                    <div class="tab-content">
                        <div class="tab-pane text-muted active show" id="request-document" role="tabpanel">
                            <form action="{{ route('loan.case', $loanapplication['id']) }}" method="post">
                                @csrf
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-3">
                                    <p class="mb-2 text-muted">Case Number:</p>
                                    <input type="text" class="form-control" readonly
                                        value="{{ $loan_helper->generateCaseNumber() }}" name="case_number" />
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-3">
                                    <p class="mb-2 text-muted">Topic:</p>
                                    <input type="text" class="form-control" name="topic" />
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-3">
                                    <p class="mb-2 text-muted">Case Detail:</p>
                                    <textarea id="content" class="form-control" rows="15" name="description"></textarea>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-3">
                                    <p class="mb-2 text-muted">Priority:</p>
                                    <select class="form-control" name="priority" data-trigger>
                                        <option value="High">High</option>
                                        <option value="Medium">Medium</option>
                                        <option value="Low">Low</option>
                                    </select>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-3">
                                    <p class="mb-2 text-muted">Status:</p>
                                    <select class="form-control" name="status" data-trigger>
                                        <option value="Open">Open</option>
                                        <option value="In Progress">In Progress</option>
                                        <option value="On Hold">On Hold</option>
                                        <option value="Resolved">Resolved</option>
                                        <option value="Reassigned">Reassigned</option>
                                        <option value="Pending Customer">Pending Customer</option>
                                        <option value="Pending Review">Pending Review</option>
                                        <option value="Closed">Closed</option>
                                        <option value="Escalated">Escalated</option>
                                        <option value="Rejected">Rejected</option>
                                        <option value="Suspended">Suspended</option>
                                        <option value="Under Investigation">Under Investigation
                                        </option>
                                        <option value="Waiting for Approval">Waiting for Approval
                                        </option>
                                        <option value="Canceled">Canceled</option>
                                    </select>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-3">
                                    <p class="mb-2 text-muted">Assigned to:</p>
                                    <select class="form-control" name="assigned_to" data-trigger>
                                        @foreach ($loan_helper->getAdminUsers() as $admin)
                                            @if (Auth::id() == $admin->id)
                                                <option value="{{ $admin->id }}">
                                                    {{ $admin->first_name }} (Me)</option>
                                            @else
                                                <option value="{{ $admin->id }}">
                                                    {{ $admin->first_name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-3">
                                    <p class="mb-2 text-muted">Methods:</p>
                                    <select class="form-control" name="method" data-trigger>
                                        <option value="all">All</option>
                                        <option value="mail">Email</option>
                                        <option value="sms">SMS</option>
                                        <option value="inapp">In App</option>
                                    </select>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-3 mb-3">
                                    <p class="mb-2 text-muted">Follow up date</p>
                                    <input type="datetime-local" class="form-control" id="input-datetime-local"
                                        name="next_follow_up">
                                </div>
                                <button type="submit" class="btn btn-primary btn-wave mt-3">Create
                                    Ticket</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
