@extends('layouts.admin')
@inject('dashboard_helper', 'App\Http\Helpers\DashboardHelper')
@inject('loan_helper', 'App\Http\Helpers\LoanHelper')
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        @include('admin.general.pageheader')
        <!-- Page Header Close -->
    </div>


    <div class="row mx-2">
        @include('admin.dashboard.moonova')
        <div class="col-xl-9">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">{{ $title }} </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <form action="{{ route('wallet.process') }}" method="post" enctype="multipart/form-data"
                            class="row g-3 mt-0">
                            @csrf
                            <input type="hidden" name="method" value="{{$method}}" />
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <p class="mb-2 text-muted">Bank:</p>
                                <select class="form-control" data-trigger name="institution" id="choices-single-default">
                                    <option value="">Select Bank</option>
                                    @foreach ($bankList as $b)
                                        <option value="{{ $b->basiq_code }}">{{ $b->name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('accountName'))
                                    <span class="error_msg" role="alert">{{ $errors->first('accountName') }}</span>
                                @endif
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <p class="mb-2 text-muted">Account Name:</p><input type="text" name="accountName"
                                    class="form-control" id="input" value="{{ old('accountName') }}" required>
                                @if ($errors->has('accountName'))
                                    <span class="error_msg" role="alert">{{ $errors->first('accountName') }}</span>
                                @endif
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <p class="mb-2 text-muted">BSB:</p><input type="text" name="bsb" class="form-control"
                                    id="input" value="{{ old('bsb') }}" required maxlength="6">
                                @if ($errors->has('bsb'))
                                    <span class="error_msg" role="alert">{{ $errors->first('bsb') }}</span>
                                @endif
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <p class="mb-2 text-muted">Account Number:</p><input type="text" name="accountNo"
                                    class="form-control" id="input" value="{{ old('accountNo') }}" required>
                                @if ($errors->has('accountNo'))
                                    <span class="error_msg" role="alert">{{ $errors->first('accountNo') }}</span>
                                @endif
                            </div>
                            <div class="mt-2 col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <p class="mb-2 text-muted">Amount:</p>
                                <div class="input-group "> <span class="input-group-text">$</span>
                                    <input type="text" name="amount" value="{{ old('amount') }}" class="form-control"
                                        aria-label="Amount (to the nearest dollar)" required>
                                    @if ($errors->has('amount'))
                                        <span class="error_msg" role="alert">{{ $errors->first('amount') }}</span>
                                    @endif
                                </div>


                            </div>
                            <div class="mt-2 col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <p class="mb-2 text-muted">Remarks:</p><input type="text" name="remarks"
                                    class="form-control" id="input" value="{{ old('remarks') }}" required>
                                @if ($errors->has('remarks'))
                                    <span class="error_msg" role="alert">{{ $errors->first('remarks') }}</span>
                                @endif
                            </div>
                            <div class="mt-2 col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <div class="row">
                                    <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12">
                                        <p class="mb-2 text-muted">Transaction Code 1:</p><input type="text" name="otp[]"
                                            class="form-control" id="input" required maxlength="4">
                                        @if ($errors->has('otp'))
                                            <span class="error_msg" role="alert">{{ $errors->first('otp') }}</span>
                                        @endif
                                        <p class="mb-2 mt-2 text-muted">Transaction Code 2:</p><input type="text" name="otp[]"
                                            class="form-control" id="input" required maxlength="4">
                                        @if ($errors->has('otp'))
                                            <span class="error_msg" role="alert">{{ $errors->first('otp') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                                        <button type="button" id="sendTransacationCode"
                                            class="mt-4 btn btn-primary btn-wave btn-lg waves-effect waves-light">Generate
                                            Transaction Code</button>
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid mt-4"> <button type="submit"
                                    class="btn btn-success btn-wave btn-lg waves-effect waves-light">Transfer</button>
                            </div>
                    </div>
                    </form>

                </div>
            </div>

        </div>

    </div>
@endsection
