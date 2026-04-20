@extends('layouts.admin')
@inject('loan_helper', 'App\Http\Helpers\LoanHelper')
@section('content')
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Case Management</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Case Management</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Start:: row-5 -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            {{ count($case) }} Loan Cases
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="deleted-table table-responsive">
                            <table id="delete-datatable" class="table table-search text-nowrap mt-4 mb-4 mb-4">
                                <thead>
                                    <tr>
                                        <th>Case Number</th>
                                        <th>Application ID</th>
                                        <th>Topic</th>
                                        <th>Priority</th>
                                        <th>Created By</th>
                                        <th>Next Follow Up</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($case as $c)
                                        <tr>
                                            <td>{{ $c->case_number }}</td>
                                            <td>{{ $c->	loan_application_id }}</td>
                                            <td>{{ $c->topic }}</td>
                                            <td>{{ $c->priority }}</td>
                                             @php
                                                    $user = $loan_helper->getUser($c->created_by);
                                                @endphp
                                            <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                            <td>{{ $c->next_follow_up }}</td>
                                            <td>
                                                @if ($c->status == 'Open')
                                                    <span class="badge bg-success">{{ ucfirst($c->status) }}</span>
                                                @elseif($c->status == 'Closed')
                                                    <span class="badge bg-danger">{{ ucfirst($c->status) }}</span>
                                                @else
                                                    <span class="badge bg-primary">{{ ucfirst($c->status) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $c->created_at}}</td>
                                            <td>
                                                <div class="mb-md-0 mb-2">
                                                    <a href="{{ route('case.view', ['id' => $c->id]) }}"
                                                        class="btn btn-icon btn-success-transparent rounded-pill btn-wave">
                                                        <i class="ri-eye-line"></i>
                                                    </a>
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
