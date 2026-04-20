@extends('layouts.admin')
@inject('loan_helper', 'App\Http\Helpers\LoanHelper')
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        @include('admin.general.pageheader')
        <!-- Page Header Close -->


        <!-- Start:: row-5 -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            <i class="bi bi-person-check text-secondary "></i> &nbsp {{ $breadcrumb['function'] }}s
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="deleted-table table-responsive ">
                            <div class="justify-content-between">
                                <span class="fw-bold text-dark data-table-btn">{{ count($loans) }} Records</span>
                            </div>
                            <table id="delete-datatable" class="table table-search text-nowrap mb-4 mt-4">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User</th>
                                        <th>Amount</th>
                                        <th>Duration</th>
                                        <th>Frequency</th>
                                        <th>Lead Id</th>
                                        <th>Approved Amount</th>
                                        <th>Stage Detail</th>
                                        <th>Application Date</th>
                                        <th>iTRS Score</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($loans as $loan)
                                        @if ($loan_helper->checkRiskFactor($loan->user_id) == 'low')
                                            <tr class="bg-success">
                                            @elseif($loan_helper->checkRiskFactor($loan->user_id) == 'medium')
                                            <tr class="bg-warning">
                                            @elseif (isset($loan->leadMarketBuy->lead_id))
                                            <tr style="background:#282c30">
                                            @elseif($loan->user->risk_flag)
                                            <tr class="bg-danger">
                                            @else
                                            <tr>
                                        @endif
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $loan->user->first_name ?? '' }} {{ $loan->user->middle_name ?? '' }}
                                            {{ $loan->user->last_name ?? '' }}</td>
                                        <td>$ {{ $loan->amount }}</td>
                                        <td>{{ $loan->duration }} weeks</td>
                                        <td>{{ ucfirst($loan->frequency) }}</td>
                                        <td>{{ $loan->leadMarketBuy->lead_id ?? 'N/A' }}</td>
                                        <td>{{ $loan->approved_amount > 0 ? '$ ' . $loan->approved_amount : 'N/A' }}
                                            @if ($loan->customer_confirmation)
                                                <i class='bx bxs-check-circle text-success'></i>
                                            @endif
                                        </td>
                                        <td>
                                            <b>Contract Signing:</b>
                                            {{ $loan->latestcontractStatus ? $loan->latestcontractStatus->status : 'Not Sent' }}
                                            <br>
                                            <b>ID Verification:</b>
                                            {{ $loan->user->id_verified == 1 ? 'Verified' : 'Not Verified' }}
                                            <br>
                                            @if (count($loan->documents) > 0)
                                                <b>Documents:</b><br>
                                                <ol>
                                                    @foreach ($loan->documents as $d)
                                                        <li>{{ $d->document_type }}: {{ $d->status }} </li>
                                                    @endforeach
                                                </ol>
                                            @endif
                                        </td>
                                        <td>{{ $loan_helper->formateDateTime($loan->application_date) }}</td>
                                        <td> {{ $loan_helper->getItrs($loan->id)}}</td>
                                        <td>
                                            @if ($loan->status == 'active')
                                                <span class="badge bg-success">{{ ucfirst($loan->status) }}</span>
                                            @elseif($loan->status == 'declined')
                                                <span class="badge bg-danger">{{ ucfirst($loan->status) }}</span>
                                            @else
                                                <span class="badge bg-primary">{{ ucfirst($loan->status) }}</span>
                                            @endif
                                        </td>
                                        <td style="background: #141414;">
                                            <div class="mb-md-0 mb-2">
                                                <a href="{{ route('loan.view', ['id' => $loan->id]) }}"
                                                    class="btn btn-icon btn-success-transparent rounded-pill btn-wave">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                @if (isset($loan->leadMarketBuy->lead_id))
                                                    <a href="{{ route('leadmarket.bought.view', ['id' => $loan->leadMarketBuy->id]) }}"
                                                        class="btn btn-icon btn-secondary-transparent rounded-pill btn-wave">
                                                        <i class="ri-flag-line"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End:: row-5 -->
    </div>
@endsection
