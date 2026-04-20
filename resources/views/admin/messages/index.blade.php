@extends('layouts.admin')
@inject('loan_helper', 'App\Http\Helpers\LoanHelper')
@section('content')
    <div class="container-fluid">
        <div class="main-chart-wrapper p-2 gap-2 d-lg-flex">
            @include('admin.messages.widgets.customerlist')
            @include('admin.messages.widgets.popup')
            <div class="main-chat-area border">
            
           </div>
        </div>
    </div>
@endsection
