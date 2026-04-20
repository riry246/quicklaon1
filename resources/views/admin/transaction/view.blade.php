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
                @include('admin.transaction.widgets.transactionDetail')
                @include('admin.transaction.widgets.fees')
                @include('admin.transaction.widgets.statement')
            </div>
        </div>
         @include('admin.transaction.widgets.revert')
    </div>
@endsection
