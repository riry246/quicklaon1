@extends('layouts.admin')
@inject('loan_helper', 'App\Http\Helpers\LoanHelper')
@section('content')
    <div class="container-fluid">

        <!-- Page Header -->
        @include('admin.general.pageheader')
        <!-- Page Header Close -->
        <!-- Start:: row-5 -->
        <div class="row">
            <div class="col-xl-3">
                @include('admin.creditScore.widgets.userDetail')
            </div>
            <div class="col-xl-9">
                 @include('admin.creditScore.widgets.summary')
            </div>
        </div>
    </div>
@endsection
