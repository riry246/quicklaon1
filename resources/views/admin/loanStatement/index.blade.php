@extends('layouts.admin')
@inject('loan_helper', 'App\Http\Helpers\LoanHelper')
@section('content')
    <div class="container-fluid">

        <!-- Page Header -->
        @include('admin.general.pageheader')
        <!-- Page Header Close -->
        <!-- Start:: row-5 -->
        <div class="row">
            <div class="col-xxl-12 col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            Loan Statement #{{ $loanapplication->id }}
                        </div>
                        <div>
                            <button class="btn btn-sm btn-success" href="javascript:void(0);" data-bs-toggle="modal"
                                data-bs-target="#newStatement">Add New Statement</button>
                            <button class="btn btn-sm btn-primary" href="javascript:void(0);" data-bs-toggle="modal"
                                data-bs-target="#update">Update Whole Statement</button>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('admin.loanStatement.overview')
                        <div class="deleted-table table-responsive">
                            <table class="table table-search- text-nowrap mt-4 mb-4 mb-4">
                                <thead>
                                    <tr>
                                        <th>Week</th>
                                        <th>Pay Date</th>
                                        <th>Opening Balance</th>
                                        <th>Weekly Payment</th>
                                        <th>Interest & fee</th>
                                        <th>Principal Payment</th>
                                        <th>Payment Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        if ($loanapplication['frequency'] == 'fortnightly') {
                                            $i = 2;
                                        } else {
                                            $i = 1;
                                        }
                                        $previousParentID = 0;
                                    @endphp
                                    @foreach ($loanstatement as $ls)
                                        @if ($ls->payment_status == 'Scheduled')
                                            <tr class="bg-light">
                                            @elseif ($ls->payment_status == 'crs')
                                            <tr class="bg-primary">
                                            @elseif ($ls->payment_status == 'Re-scheduled')
                                            <tr class="bg-secondary">
                                            @elseif ($ls->payment_status == 'Dishonoured')
                                            <tr class="bg-danger">
                                            @elseif ($ls->payment_status == 'Complete')
                                            <tr class="bg-success">
                                            @elseif ($ls->payment_status == 'WaitingOnClearedFunds')
                                            <tr class="bg-orange">
                                            @else
                                            <tr>
                                        @endif
                                        @if ($ls['frequency'] == 'fortnightly')
                                            <td> {{ $i }} weeks </td>
                                            @php $i += 2; @endphp
                                        @else
                                            <td> {{ $i++ }} week </td>
                                        @endif
                                        <td>{{ $ls['settlement_date'] }}</td>
                                        <td>$ {{ $ls['opening_balance'] }}</td>
                                        <td>$ {{ $ls['weekly_payment'] }}</td>
                                        <td>$ {{ $ls['interest'] }}</td>
                                        <td>$ {{ $ls['principal_payment'] }}</td>
                                        <td>
                                            @if ($ls['payment_status'] == 'crs')
                                                Canceled & Re-scheduled
                                            @else
                                                {{ $ls['payment_status'] }}
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-danger dropdown-toggle"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    Action
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @if (
                                                        $ls['payment_status'] !== 'WaitingOnClearedFunds' &&
                                                            $ls['payment_status'] !== 'Complete' &&
                                                            $ls['payment_status'] !== 'crs')
                                                        <li><a class="dropdown-item"
                                                                href="{{ route('loan.payment', $ls->id) }}">Process
                                                                Payment</a></li>
                                                        <li><a class="dropdown-item" href="javascript:void(0);"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#repayment-{{ $ls->id }}">Re-schedule
                                                                Payment</a></li>
                                                        <li><a class="dropdown-item" href="javascript:void(0);"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#update-{{ $ls->id }}">Update
                                                                Statement</a></li>
                                                        <li><a class="dropdown-item" href="javascript:void(0);"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#partial-{{ $ls->id }}">Partial
                                                                Payments</a></li>
                                                    @endif
                                                    <hr class="dropdown-divider">
                                                    <li><a class="dropdown-item" href="javascript:void(0);"
                                                            onclick="toggleTable('transaction_{{ $ls->id }}')">View
                                                            Transaction</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                        </tr>
                                        @if (isset($ls->transaction))
                                            @if (count($ls->transaction) > 0)
                                                <tr id="transaction_{{ $ls->id }}" class="transactions_details"
                                                    style="display:none">
                                                    <td colspan="10">
                                                        @include('admin.loanStatement.transaction')
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                        @include('admin.loanStatement.repayment')
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="modal fade" id="newStatement" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('loan.addNewPayment', $loanapplication['id']) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title">Add New Statement</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-4">
                            <div class="row gy-3 mt-2">
                                <div class="col-xl-12 col-lg-12 col-md-6 col-sm-12">
                                    <p class="mb-2 text-muted">Amount </p>
                                    <input type="text" class="form-control" name="amount"
                                        value="{{ $ls->weekly_payment }}">
                                </div>
                                <div class="col-xl-12" style="width: 500px">
                                    <label>Payment Status</label>
                                    <select class="form-control mt-2" name="payment_status">
                                        <option value="Hold">Hold</option>
                                        <option value="Complete">Completed</option>
                                        <option value="WaitingOnClearedFunds">WaitingOnClearedFunds</option>
                                        <option value="Scheduled">Scheduled</option>
                                        <option value="Re-scheduled">Re-scheduled</option>
                                        <option value="Dishonoured">Dishonoured</option>
                                        <option value="fee">Dishonoured fee</option>
                                        <option value="fee">Re-scheduled fee</option>
                                    </select>
                                </div>
                                <div class="col-xl-12" style="width: 500px">
                                    <label>Number of statement</label>
                                    <select class="form-control mt-2" name="no_of_statement">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        
                                    </select>
                                </div>
                                <div class="col-xl-12" style="width: 500px">
                                    <label>Payment Frequency</label>
                                    <select class="form-control mt-2" name="frequency">
                                        <option value="weekly">Weekly</option>
                                        <option value="fortnightly">Fortnightly</option>
                                    </select>
                                </div>
                                <div class="col-xl-12" style="width: 500px">
                                    <label>Start Date</label>
                                    <div class="input-group mt-2">
                                        <div class="input-group-text text-muted"> <i class="ri-calendar-line"></i> </div>
                                        <input type="text" name="start_date" class="form-control" id="date"
                                            placeholder="Choose date">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal fade" id="update" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('loan.reschdeuleWhole', $loanapplication['id']) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title">Update Whole Statement</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-4">
                            <div class="row gy-3 mt-2">
                                <div class="col-xl-12" style="width: 500px">
                                    <label>Payment Frequency</label>
                                    <select class="form-control mt-2" name="frequency">
                                        <option value="weekly">Weekly</option>
                                        <option value="fortnightly">Fortnightly</option>
                                    </select>
                                </div>
                                <div class="col-xl-12" style="width: 500px">
                                    <label>Start Date</label>
                                    <div class="input-group mt-2">
                                        <div class="input-group-text text-muted"> <i class="ri-calendar-line"></i> </div>
                                        <input type="text" name="start_date" class="form-control" id="date"
                                            placeholder="Choose date">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary">Approve</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
