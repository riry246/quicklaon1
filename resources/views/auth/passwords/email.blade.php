@extends('layouts.master')

@section('content')
    <div class="row authentication mx-0">
        <div class="col-xxl-12 col-xl-12 col-lg-12">
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                    <div class="p-5">
                        <div class="mb-3">
                            <a href="index.html">
                                <img src{{ asset('assets/images/brand-logos/desktop-logo.png') }}" alt=""
                                    class="authentication-brand desktop-logo">
                                <img src="{{ asset('assets/images/brand-logos/desktop-dark.png') }}" alt=""
                                    class="authentication-brand desktop-dark">
                            </a>
                        </div>
                        <p class="h5 fw-semibold mb-2">Forget Password</p>
                        <p class="mb-3 text-muted op-7 fw-normal">Please Enter Your Email Address</p>
                        <div class="btn-list">
                            <a href="{{ route('login') }}" class="btn btn-lg btn-light"><i
                                    class="ti ti-arrow-back fs-16"></i>
                                Back to Sign In</a>
                        </div>
                        <div class="text-center my-5 authentication-barrier">
                            <span>OR</span>
                        </div>
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="row gy-3">
                                <div class="col-xl-12 mt-0 mb-3">
                                    <label for="reset-password" class="form-label text-default">Email Address</label>
                                    <div class="input-group">
                                        <input type="text"
                                            class="form-control form-control-lg @error('email') is-invalid @enderror"
                                            placeholder="Email Address" name="email" value="{{ old('email') }}" required
                                            autocomplete="email" autofocus>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="col-xl-12 d-grid mt-2">
                                    <button type="submit" class="btn btn-lg btn-primary">Reset Password</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
