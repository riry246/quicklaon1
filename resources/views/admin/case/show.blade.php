@extends('layouts.admin')
@inject('loan_helper', 'App\Http\Helpers\LoanHelper')
@section('content')
    <div class="container-fluid">

        <!-- Page Header -->
        @include('admin.general.pageheader')

        <div class="row">
            <div class="col-xl-9">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            Case Details
                        </div>
                        <div>
                            <button class="btn btn-primary btn-wave" data-bs-toggle="modal" data-bs-target="#ticket">Update
                                Case</i></button>
                            <div class="modal fade" id="ticket" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h6 class="modal-title">Create Ticket</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body pt-4">
                                            <div class="col-xl-12">
                                                <div class="tab-content">
                                                    <div class="tab-pane text-muted active show" id="request-document"
                                                        role="tabpanel">
                                                        <form action="{{ route('case.update', $loanCase->id) }}"
                                                            method="post">
                                                            @csrf
                                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-3">
                                                                <p class="mb-2 text-muted">Priority:</p>
                                                                <select class="form-control" name="priority" data-trigger>
                                                                    <option name="High">High</option>
                                                                    <option name="Medium">Medium</option>
                                                                    <option name="Low">Low</option>
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
                                                                    <option value="Pending Customer">Pending Customer
                                                                    </option>
                                                                    <option value="Pending Review">Pending Review</option>
                                                                    <option value="Closed">Closed</option>
                                                                    <option value="Escalated">Escalated</option>
                                                                    <option value="Rejected">Rejected</option>
                                                                    <option value="Suspended">Suspended</option>
                                                                    <option value="Under Investigation">Under Investigation
                                                                    </option>
                                                                    <option value="Waiting for Approval">Waiting for
                                                                        Approval
                                                                    </option>
                                                                    <option value="Canceled">Canceled</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-3">
                                                                <p class="mb-2 text-muted">Assigned to:</p>
                                                                <select class="form-control" name="assigned_to" data-trigger
                                                                    id="choices-single-default">
                                                                    @foreach ($loan_helper->getAdminUsers() as $admin)
                                                                        @if (Auth::id() == $admin->id)
                                                                            <option value="{{ $admin->id }}" selected>
                                                                                {{ $admin->first_name }} (Me)</option>
                                                                        @else
                                                                            <option value="{{ $admin->id }}">
                                                                                {{ $admin->first_name }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-3">
                                                                <p class="mb-2 text-muted">Method:</p>
                                                                <select class="form-control" name="methods" data-trigger>
                                                                    <option name="Phone Call">Phone Call</option>
                                                                    <option name="Mail">Mail</option>
                                                                    <option name="SMS / Text Messages">SMS / Text Messages
                                                                    </option>
                                                                </select>
                                                            </div>
                                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-3 mb-3">
                                                                <p class="mb-2 text-muted">Next Follow up date:</p>
                                                                <input type="datetime-local" class="form-control"
                                                                    id="input-datetime-local" name="next_follow_up">
                                                            </div>
                                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-3 mb-3">
                                                                <p class="mb-2 text-muted">Comments:</p>
                                                                <textarea name="notes" class="form-control"></textarea>
                                                            </div>

                                                            <button type="submit"
                                                                class="btn btn-primary btn-wave mt-3">Update
                                                                Ticket</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="fw-semibold mb-4 task-title">
                            {{ $loanCase->topic }}
                        </h5>
                        <div class="fs-15 fw-semibold mb-2">Case Description :</div>
                        <p class="text-muted task-description">{{ $loanCase->description }}</p>


                    </div>
                    <div class="card-footer">
                        <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
                            <div>
                                <span class="d-block text-muted fs-12">Created By</span>
                                <div class="d-flex align-items-center">
                                    @php
                                        $user = $loan_helper->getUser($loanCase->created_by);
                                    @endphp

                                    <span class="d-block fs-14 fw-semibold">{{ $user->first_name }}
                                        {{ $user->last_name }}</span>
                                </div>
                            </div>
                            <div>
                                <span class="d-block text-muted fs-12">Start Date</span>
                                <span
                                    class="d-block fs-14 fw-semibold">{{ date('d M, Y', strtotime($loanCase->created_at)) }}</span>
                            </div>
                            <div>
                                <span class="d-block text-muted fs-12">Next Follow up </span>
                                <span
                                    class="d-block fs-14 fw-semibold">{{ date('d M, Y', strtotime($loanCase->next_follow_up)) ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="d-block text-muted fs-12">Assigned To</span>
                                @php
                                    $user = $loan_helper->getUser($assignmentHistories->admin_user_id);
                                @endphp

                                <span class="d-block fs-14 fw-semibold">{{ $user->first_name }}
                                    {{ $user->last_name }}</span>
                            </div>
                            <div>
                                <span class="d-block text-muted fs-12">Status</span>
                                @if ($loanCase->status == 'Open')
                                    <span class="d-block"><span
                                            class="badge bg-success">{{ $loanCase->status }}</span></span>
                                @elseif($loanCase->status == 'Closed')
                                    <span class="d-block"><span
                                            class="badge bg-danger">{{ $loanCase->status }}</span></span>
                                @else
                                    <span class="d-block"><span
                                            class="badge bg-primary">{{ $loanCase->status }}</span></span>
                                @endif
                            </div>
                            <div>
                                <span class="d-block text-muted fs-12">Priority</span>
                                <span class="d-block fs-14 fw-semibold"><span
                                        class="badge bg-success-transparent">{{ $loanCase->priority }}</span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Case Discussions</div>
                    </div>
                    <div class="card-body">
                        @if (count($followupHistories) > 0)
                            <ul class="list-unstyled profile-timeline">
                                @foreach ($followupHistories as $fh)
                                    <li>
                                        <div>
                                            @php
                                                $user = $loan_helper->getUser($fh->follow_up_by);
                                            @endphp
                                            <span
                                                class="avatar avatar-sm bg-secondary avatar-rounded profile-timeline-avatar">
                                                {{ strtoupper(substr($user->first_name, 0, 1)) }}
                                            </span>
                                            <p class="mb-2">

                                                <b>{{ $user->first_name }} {{ $user->last_name }}</b> via <b
                                                    class="text-secondary">{{ $fh->methods }}</b>
                                                <span
                                                    class="float-end fs-11 text-muted">{{ date('d M, Y - H:i', strtotime($fh->follow_up_date)) }}</span>
                                            </p>
                                            <p class="text-muted mb-0">
                                                {{ $fh->notes }}
                                            </p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>No case discussions found</p>
                        @endif
                    </div>


                </div>
            </div>
            <div class="col-xl-3">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            Application Detail
                        </div>

                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled crm-top-deals mb-0">
                            <li>
                                <div class="d-flex align-items-top flex-wrap">
                                    <div class="flex-fill">
                                        <p class="fw-semibold mb-0">Application Number</p>
                                        <a href="{{ route('loan.view',['id'=>$loan_application->id]) }}"><span class="text-muted fs-12 text-secondary"> #{{ $loan_application->id }}</span></a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex align-items-top flex-wrap">
                                    <div class="flex-fill">
                                        <p class="fw-semibold mb-0">Applicant Name</p>
                                        <span class="text-muted fs-12"> {{ $applicant->first_name }}
                                            {{ $applicant->middle_name }}
                                            {{ $applicant->last_name }}</span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex align-items-top flex-wrap">
                                    <div class="flex-fill">
                                        <p class="fw-semibold mb-0">Applicant Email</p>
                                        <span class="text-muted fs-12"> {{ $applicant->email }} </span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex align-items-top flex-wrap">
                                    <div class="flex-fill">
                                        <p class="fw-semibold mb-0">Applicant Mobile</p>
                                        <span class="text-muted fs-12"> {{ $applicant->mobile }} </span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Assinged Team</div>
                    </div>
                    <div class="card-body">
                        @php
                            $user = $loan_helper->getUser($loanCase->created_by);
                        @endphp
                        <ul class="list-unstyled crm-top-deals mb-0">
                            <li>
                                <div class="d-flex align-items-top flex-wrap">
                                    <div class="me-2">
                                        <span class="avatar avatar-sm bg-secondary avatar-rounded profile-timeline-avatar">
                                            {{ strtoupper(substr($user->first_name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="flex-fill">
                                        <p class="fw-semibold mb-0">{{ $user->first_name }} {{ $user->middle_name }}
                                            {{ $user->last_name }}</p>
                                        <span class="text-muted fs-12">Created By</span>
                                    </div>
                                </div>
                            </li>
                            @php
                                $user = $loan_helper->getUser($assignmentHistories->admin_user_id);
                            @endphp
                            <li>
                                <div class="d-flex align-items-top flex-wrap">
                                    <div class="me-2">
                                        <span class="avatar avatar-sm bg-secondary avatar-rounded profile-timeline-avatar">
                                            {{ strtoupper(substr($user->first_name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="flex-fill">
                                        <p class="fw-semibold mb-0">{{ $user->first_name }} {{ $user->middle_name }}
                                            {{ $user->last_name }}</p>
                                        <span class="text-muted fs-12">Assigned to</span>
                                    </div>
                                </div>
                            </li>
                            @php
                                $distinctData = $followupHistories->pluck('follow_up_by')->unique();
                            @endphp
                            @foreach($distinctData as $item)
                            @php
                                $user = $loan_helper->getUser($item);
                            @endphp
                            <li>
                                <div class="d-flex align-items-top flex-wrap">
                                    <div class="me-2">
                                        <span class="avatar avatar-sm bg-secondary avatar-rounded profile-timeline-avatar">
                                            {{ strtoupper(substr($user->first_name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="flex-fill">
                                        <p class="fw-semibold mb-0">{{ $user->first_name }} {{ $user->middle_name }}
                                            {{ $user->last_name }}</p>
                                        <span class="text-muted fs-12">Follow up by</span>
                                    </div>
                                </div>
                            </li>
@endforeach
                        </ul>



                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection
