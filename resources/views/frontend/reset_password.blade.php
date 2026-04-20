@extends('layouts.main')

@section('content')
    <section class="section smp-0 p-5 section_landing mx-auto" id="steps">
        <div class="container text-center">
            <div class="row text-start">
                <div class="col-12 col-md-12">
                     @include('frontend/widgets/logo')
                    <div class="card custom-card noshadow">
                        <div class="card-body rounded p-0">
                            <div class="row mx-auto ">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 p-5 smp-4 authentication ">
                                    @include('frontend.widgets.messages')
                                    <div class="p-5 smp-0">
                                        <h2 class="text-center">Reset Password </h2>
                                        <p class="text-center subheading px-5 pt-4">Securely regain access to your account</p>
                                        <div class="row  mt-4">
                                            <form action={{ route('sendResetPassword') }} method="post">
                                                @csrf
                                                <div class="col-xl-12">
                                                    <label for="signin-username" class="form-label text-default">Email
                                                        Address</label>
                                                    <input type="text" class="form-control form-control-lg"
                                                        id="signin-username" name="email" placeholder="Email Address"
                                                        required>
                                                </div>
                                                <div class="col-xl-12 d-grid mt-4">
                                                    <button type="submit" class="btn btn-lg btn-secondary">Reset
                                                        Password</button>
                                                </div>
                                                <div class="text-center my-5 authentication-barrier">
                                                    <span>OR</span>
                                                </div>
                                                <div class="d-grid gap-2 col-12 ">
                                                    <a href="{{ route('home') }}"
                                                        class="btn btn-lg btn-outline-secondary label-btn ">
                                                        Login
                                                    </a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @include('frontend/widgets/bigLogo')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
