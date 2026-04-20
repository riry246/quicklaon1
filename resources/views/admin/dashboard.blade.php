@extends('layouts.admin')
@inject('dashboard_helper', 'App\Http\Helpers\DashboardHelper')
@inject('setting_helper', 'App\Http\Helpers\SettingHelper')
@inject('loan_helper', 'App\Http\Helpers\LoanHelper')
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        @include('admin.general.pageheader')
        <!-- Page Header Close -->
        <!-- Start::row-1 -->
         @if($setting_helper->getAdminGroup() == 'credit-assessor')
         @else
        <div class="row dashboard-top-widgets">
            @include('admin.dashboard.activeLoan')
            @include('admin.dashboard.revenue')
            @include('admin.dashboard.arrears')
            @include('admin.dashboard.loanDetail')
        </div>
        <!--End::row-1 -->
        <!-- Start::row-2 -->
        <div class="row">
            <div class="col-lg-9 col-sm-12 col-md-9 col-xxl-9 col-xl-9">
                @include('admin.dashboard.tabContent')
                <div class="col-12" id="totalLending"></div>
                <div class="col-12" id="arrearsOverview"></div>
                <div class="col-12" id="longTermDebtFundAnalysis"></div>
                <div class="col-12" id="originationOverview"></div>
                <div class="col-12" id="paymentCollectionsOverview"></div>
                @include('admin.dashboard.badDebt')
            </div>
            <div class="col-lg-3 col-sm-12 col-md-3 col-xxl-3 col-xl-3">
                @include('admin.dashboard.todayTransaction')
                
                @include('admin.dashboard.recentSettlement')
                @include('admin.dashboard.activities')
                @include('admin.dashboard.downloadReview')
            </div>
        </div>
        @endif
    </div>
@endsection
