@extends('layouts.main')

@section('content')
    <section class="section smp-0 section_landing mx-auto" id="steps">
        <div class="container text-center">
            <div class="row text-start">
                <div class="col-12 col-md-12">
                    @include('frontend/widgets/logo')
                    <div class="card custom-card noshadow">
                        <div class="card-body rounded p-0">
                            <div class="row mx-auto  ">
                                <div
                                    class="col-xl-6 col-lg-6 col-md-6 col-sm-12 pt-5  authentication d-flex align-items-center justify-content-between">
                                    <div class="p-5 smp-0">

                                        <h2 class="text-center">Welcome Back 👋</h2>
                                        <p class="text-center subheading px-5 pt-4">Ready to view your current Quickloan
                                            dashboard or apply for a new loan instantly? Get started below</p>
                                        <div class="row  mt-5">
                                            @include('frontend.widgets.messages')
                                            <form action={{ route('user.login') }} method="post">
                                                @csrf
                                                <div class="col-xl-12">
                                                    <label for="signin-username" class="form-label text-default">Email
                                                        Address</label>
                                                    <input type="text" class="form-control form-control-lg"
                                                        id="signin-username" name="email" placeholder="Email Address"
                                                        required>
                                                </div>
                                                <div class="col-xl-12 mb-3 mt-3">
                                                    <label for="signin-password"
                                                        class="form-label text-default d-block">Password<a
                                                            href="{{ route('resetPassword') }}"
                                                            class="float-end text-danger">Forget
                                                            password
                                                            ?</a></label>
                                                    <div class="input-group">
                                                        <input type="password" class="form-control form-control-lg"
                                                            id="signin-password" placeholder="Password" name="password"
                                                            required>
                                                        <button class="btn btn-light" type="button"
                                                            onclick="createpassword('signin-password',this)"
                                                            id="button-addon2"><i
                                                                class="ri-eye-off-line align-middle"></i></button>
                                                    </div>
                                                    <div class="mt-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value=""
                                                                id="defaultCheck1">
                                                            <label class="form-check-label text-muted fw-normal"
                                                                for="defaultCheck1">
                                                                Remember password ?
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-12 d-grid mt-2">
                                                    <button type="submit" class="btn btn-lg btn-secondary">Sign In</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="text-center my-5 authentication-barrier">
                                            <span>OR</span>
                                        </div>
                                        <apply-component></apply-component>
                                        <p class="text-center subheading px-5 pt-4">Begin your loan application with
                                            CashFaster to
                                            receive in outcome instantly!</p>
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
    <script>
        function createpassword(inputId, button) {
            var passwordInput = document.getElementById(inputId);

            // Toggle the input type between "password" and "text"
            passwordInput.type = (passwordInput.type === "password") ? "text" : "password";

            // Change the eye icon based on the input type
            var eyeIcon = button.querySelector('i');
            eyeIcon.className = (passwordInput.type === "password") ? "ri-eye-off-line align-middle" :
                "ri-eye-line align-middle";
        }
    </script>
@endsection
