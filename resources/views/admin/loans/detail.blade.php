@extends('layouts.admin')
@inject('loan_helper', 'App\Http\Helpers\LoanHelper')
@inject('dashboard_helper', 'App\Http\Helpers\DashboardHelper')
@section('content')
    <div class="container-fluid">
        @if ($loanapplication->viewed_by_user_id && $loanapplication->viewed_by_user_id != $currentUser->id)
            @include('admin.loans.widgets.viewing')
        @else
            <!-- Page Header -->
            @include('admin.general.pageheader')
            <!-- Page Header Close -->
            <!-- Start:: row-5 -->
            <div class="row">
                <div class="col-xl-9">
                    @include('admin.loans.widgets.alert')
                    @include('admin.loans.widgets.nav')
                    @include('admin.loans.widgets.userDetail')
                    @include('admin.loans.widgets.tabContent')
                </div>
                <div class="col-xl-3">
                    @include('admin.loans.widgets.userProfile')
                    @include('admin.loans.widgets.warning')
                    @include('admin.loans.widgets.scoring')
                    @include('admin.loans.widgets.checklist')
                    @include('admin.loans.widgets.illionBank')
                    @include('admin.loans.widgets.request')
                    @include('admin.loans.widgets.loanConfirmation')
                    @include('admin.loans.widgets.upcomingFollowup')
                    @include('admin.loans.widgets.leadMarket')
                    @include('admin.loans.widgets.leadMarketBuy')
                    @include('admin.loans.widgets.marketing')
                </div>
                @include('admin.loans.widgets.assignApplication')
            </div>
            <script>
                const loanApplicationId = '{{ $loanapplication->id }}';
                const inactivityTimeLimit = 600000; // 10 minutes in milliseconds

                let inactivityTimer;

                function clearViewingStatus() {
                    fetch(`{{ route('loan-application.clear-view-status', $loanapplication->id) }}`, {
                        method: 'GET',
                    });
                }

                function resetInactivityTimer() {
                    clearTimeout(inactivityTimer);
                    inactivityTimer = setTimeout(clearViewingStatus, inactivityTimeLimit);
                }

                window.addEventListener('beforeunload', clearViewingStatus);
                indow.addEventListener('unload', handleTabClosure); // Handle tab closure directly

                document.addEventListener('mousemove', resetInactivityTimer);
                document.addEventListener('keydown', resetInactivityTimer);

                resetInactivityTimer(); // Initialize the inactivity timer
            </script>
        @endif
    </div>
@endsection
