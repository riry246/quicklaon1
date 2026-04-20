@extends('layouts.master')

@section('content')
<div class="row authentication mx-0">
    <div class="col-xxl-6 col-xl-6 col-lg-6">
        <div class="row justify-content-center align-items-center h-100">
            <div class="col-xxl-8 col-xl-8 col-lg-8 col-md-8 col-sm-8 col-12">
                <div class="p-5 ">

                    <p class="h5 fw-semibold mb-2 text-center">2FA authentication</p>
                    <p class="mb-3 text-muted op-7 fw-normal text-center">Welcome to Cashfaster CRM</p>

                    <div class="text-center my-5 authentication-barrier">

                    </div>
                    <form method="POST" action="{{ route('2fa') }}">
                        @csrf
                        <div class="row gy-3">
                            <div class="col-xl-12 mt-0">
                                <label for="signin-username" class="form-label text-default">Auth Code</label>
                                <input type="text"
                                    class="form-control form-control-lg @error('one_time_password') is-invalid @enderror"
                                    name="one_time_password" value="" placeholder="Auth Code">
                                @if($errors->any())
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{$errors->first()}}</strong>
                                </span>
                               @endif
                            </div>
                            <div class="col-xl-12 d-grid mt-2">
                                <button type="submit" class="btn btn-lg btn-primary">Sign In</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('admin.widgets.loginSlider')
</div>
@endsection