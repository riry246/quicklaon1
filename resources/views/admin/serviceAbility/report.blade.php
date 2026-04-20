@extends('layouts.admin')
@inject('loan_helper', 'App\Http\Helpers\LoanHelper')
@section('content')
    <div class="container-fluid">

        <!-- Page Header -->
        @include('admin.general.pageheader')
        <!-- Page Header Close -->
        <div class="container ">
            @include('admin.serviceAbility.report.detailcard')
        </div>
    </div>
@endsection
