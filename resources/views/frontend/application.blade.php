@extends('layouts.main')

@section('content')

    @if ($data['step'] !== 'mobile-verify')
    @include('frontend/widgets/sidebar')
        <loandetail-component :json-data="{{ json_encode($data) }}"></loandetail-component>
    @endif
    <section class="section section_landing mx-auto smp-0" id="steps">
        <div class="container text-center ">
            <div class="row text-start">
                <div class="col-12 col-md-12">
                    @if ($data['step'] == 'mobile-verify')
                        @include('frontend/widgets/logo')
                    @endif
                    <div class="card custom-card noshadow">
                        <div class="card-body rounded p-0">
                            <div class="row mx-auto ">
                                @if ($data['step'] == 'mobile-verify')
                                    <div
                                        class="col-xl-6 col-lg-6 col-md-6 col-sm-12  authentication d-flex align-items-center justify-content-between">
                                        <mobileverify-component
                                            :json-data="{{ json_encode($data) }}"></mobileverify-component>
                                    </div>
                                    @include('frontend/widgets/bigLogo')
                                @else
                                    <application-component></application-component>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
