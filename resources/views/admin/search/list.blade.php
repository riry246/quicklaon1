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
            <div class="col-xl-12">
                @include('admin.search.widgets.tab')
            </div>
        </div>
    </div>
@endsection
