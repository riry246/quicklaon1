@extends('layouts.admin')
@inject('loan_helper', 'App\Http\Helpers\LoanHelper')
@section('content')
    <div class="container-fluid">

        <!-- Page Header -->
        @include('admin.general.pageheader')
        <!-- Page Header Close -->
        <div class="container ">
            <nav class="navbar bg-light mt-4 mb-4 align-items-center justify-content-between">
                <form class="container-fluid ">
                    <h6 class="m-0">Statement</h6>
                    <div>
                        <a href="{{ route('bankStatement.update', $basiq_user_id) }}" class="btn btn-primary m-1"
                            type="button">Update </a>
                        <a href="{{ route('bankStatement.affordability', $basiq_user_id) }}" class="btn btn-secondary m-1"
                            type="button">View Affordability Statement</a>

                        <a href="{{ route('bankStatement.consumer', $basiq_user_id) }}" class="btn btn-secondary m-1"
                            type="button">View Consumer Affordability Statement</a>
                    </div>
                </form>
            </nav>
            @include('admin.bankDetail.accounts')
            @include('admin.bankDetail.statements')

        </div>
    </div>
@endsection
