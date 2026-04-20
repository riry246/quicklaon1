@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Wheel Of Fortune</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Wheel Of Fortune</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->
        <div class="row">
            <div class="col-xl-9">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title ">Wheel Of Fortune
                        </div>
                    </div>
                    <div class="card-body m-5">
                        <div class="wheel-of-fortune mx-auto">
                            <div class="spinBtn">Spin</div>
                            <div class="wheel">
                                <div class="number" style="--i:1;--clr:#db7093" data-prize="100"><span>100</span></div>
                                <div class="number" style="--i:2;--clr:#20b2aa" data-prize="200"><span>200</span></div>
                                <div class="number" style="--i:3;--clr:#d63e92" data-prize="300"><span>300</span></div>
                                <div class="number" style="--i:4;--clr:#ff340f" data-prize="400"><span>400</span></div>
                                <div class="number" style="--i:5;--clr:#ff7f50" data-prize="500"><span>500</span></div>
                                <div class="number" style="--i:6;--clr:#3cb371" data-prize="600"><span>600</span></div>
                                <div class="number" style="--i:7;--clr:#4169e1" data-prize="700"><span>700</span></div>
                                <div class="number" style="--i:8;--clr:#daa520" data-prize="800"><span>800</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            Winner Detail
                        </div>

                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled crm-top-deals mb-0">
                            <li>
                                <div class="d-flex align-items-top flex-wrap">
                                    <div class="flex-fill">
                                        <p class="fw-bold mb-0 fs-6">Price Amount</p>
                                         <span class="text-muted fs-5 fw-semibold" id="amountValue">$0</span>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex align-items-top flex-wrap">
                                    <div class="flex-fill">
                                        <p class="fw-bold mb-0 fs-6">Winner Name</p>
                                        <span class="text-muted fs-5 fw-semibold" id="user"></span>
                                        <span class="text-muted fs-5 fw-semibold d-none" id="temp_user">{{ $user->first_name}} {{ $user->middle_name}} {{ $user->last_name}}</span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endsection
