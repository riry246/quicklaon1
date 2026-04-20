@extends('layouts.admin')
@inject('loan_helper', 'App\Http\Helpers\LoanHelper')
@inject('transaction_helper', 'App\Http\Helpers\TransactionHelper')
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        @include('admin.general.pageheader')
        <!-- Page Header Close -->
        <!-- Start:: row-5 -->
        <div class="row">
            <div class="col-xl-9">
                @include('admin.transaction.principle.tab')
                @include('admin.transaction.widgets.list')
            </div>
            <div class="col-xl-3">
               @include('admin.transaction.principle.completed')
            </div>
        </div>
    </div>
@endsection
