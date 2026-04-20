@extends('layouts.contract')

@section('content')
    <loandetail-component :json-data="{{ json_encode($data) }}"></loandetail-component>
    <section class="section bg-light" id="steps">
        <div class="container  offmargin">
            <contract-signing-admin-component :val="{{ json_encode($data) }}"></contract-signing-admin-component>
        </div>
    </section>
@endsection
