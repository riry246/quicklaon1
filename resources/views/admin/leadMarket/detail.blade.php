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
               @include('admin.leadMarket.widgets.userDetail')
               @if(isset($detail['Data']['Lead']['Applicant']['Employment_History']))
               @include('admin.leadMarket.widgets.employment')
               @endif
               @if(isset($detail['Data']['Lead']['Buyer_Data']))
               @include('admin.leadMarket.widgets.buyer')
               @endif
               @if(isset($detail['Data']['Lead']['Seller_Data']))
               @include('admin.leadMarket.widgets.seller')
               @endif
            </div>
            
        </div>
    </div>
@endsection
