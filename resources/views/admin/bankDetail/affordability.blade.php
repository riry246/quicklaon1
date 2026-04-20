@extends('layouts.admin')
@inject('loan_helper', 'App\Http\Helpers\LoanHelper')
@section('content')
    <div class="container-fluid">

        <!-- Page Header -->
        @include('admin.general.pageheader')
        <!-- Page Header Close -->
        <div class="container pt-4">
            <nav class="navbar bg-light mt-4 mb-4">
                <form class="container-fluid justify-content">
                    <h6><span class="fs-12">Affordability Report</span> <br/>Summary</h6>
                    <div>
                    <a href="{{ route('bankStatement.affordability.create', $basiq_user_id) }}" class="btn btn-primary m-1"
                        type="button">Update</a>
                    <a href="{{ route('bankStatement', $basiq_user_id) }}" class="btn btn-secondary m-1"
                        type="button">View Individual Statement </a>
                        </div>
                </form>
            </nav>
            @include('admin.bankDetail.affordability.detailcard')
        </div>
    </div>
@endsection
