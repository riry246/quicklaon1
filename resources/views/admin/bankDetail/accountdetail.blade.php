@extends('layouts.admin')
@inject('loan_helper','App\Http\Helpers\LoanHelper')
@section('content')
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Bank Statement</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Bank Statement</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->
        <div class="container">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            {{ $account['name'] }}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between gap-2">
                            <div>
                                <span class="d-block text-muted fs-12 fw-normal">Account Holder</span>
                                <span class="fw-semibold">{{ $account['accountHolder'] }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <div>
                                    <span class="d-block text-muted fs-12 fw-normal">Account Number</span>
                                    <span class="fw-semibold">{{ $account['accountNo'] }}</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <div>
                                    <span class="d-block text-muted fs-12 fw-normal">Balance</span>
                                    <span class="fw-semibold">{{ $account['currency'] }} {{ $account['balance'] }}</span>
                                </div>
                            </div>
                            <div>
                                <span class="d-block text-muted fs-12 fw-normal">Available Fund</span>
                                <span class="fw-semibold">{{ $account['currency'] }} {{ $account['availableFunds'] }}</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between gap-2 mt-3">
                            <div class="d-flex align-items-center gap-2">
                                <div>
                                    <span class="d-block text-muted fs-12 fw-normal">Account Status</span>
                                    <span class="fw-semibold">{{ ucfirst($account['status']) }}</span>
                                </div>
                            </div>
                            @if ($account['creditLimit'])
                                <div>
                                    <span class="d-block text-muted fs-12 fw-normal">Credit Limit</span>
                                    <span class="fw-semibold">{{ $account['currency'] }}
                                        {{ $account['creditLimit'] }}</span>
                                </div>
                            @endif
                        </div>

                    </div>
                    <div class="card-footer">
                        <p class="card-text mt-2"><small class="text-muted">Last updated :
                                {{ $account['lastUpdated'] }}</small>
                        </p>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xxl-12 col-xl-12">
                    <div class="card custom-card">
                        <div class="card-header">
                            <div class="card-title">
                                Statement
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="deleted-table table-responsive">
                                <div class="text-center">

                                </div>
                                <table id="delete-datatable" class="table table-search text-nowrap mt-4 mb-4">
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>Post Date</th>
                                            <th>Account</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Debit</th>
                                            <th>Credit</th>


                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($statements as $key => $s)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $s['postDate'] }}</td>
                                                <td>{{ $account['name'] }}</td>
                                                <td>{{ $s['description'] }}</td>
                                                <td>{{ $s['status'] }}</td>
                                                @if ($s['direction'] == 'debit')
                                                    <td>{{ $s['amount'] }}</td>
                                                @else
                                                    <td></td>
                                                @endif
                                                @if ($s['direction'] == 'credit')
                                                    <td>{{ $s['amount'] }}</td>
                                                @else
                                                    <td></td>
                                                @endif

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
