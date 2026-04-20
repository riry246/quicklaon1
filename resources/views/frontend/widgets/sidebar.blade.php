<!-- Start::app-sidebar -->
<aside class="app-sidebar sticky" id="sidebar">

    <div class="container p-0">
        <!-- Start::main-sidebar -->
        <div class="main-sidebar">

            <!-- Start::nav -->
            <nav class="main-menu-container nav nav-pills sub-open">
                <div class="landing-logo-container">
                    <div class="horizontal-logo">
                        <a href="{{ route('user.login') }}" class="header-logo">
                            <img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="desktop-logo logo-small ">
                            <img src="{{ asset('assets/images/logo.png') }}" alt="logo"
                                class="desktop-white logo-small ">
                        </a>
                    </div>
                </div>
                <div class="slide-left" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                        width="24" height="24" viewBox="0 0 24 24">
                        <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                    </svg></div>

                <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                        width="24" height="24" viewBox="0 0 24 24">
                        <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z">
                        </path>
                    </svg></div>
                <div class="d-lg-flex d-none">
                    <div class="btn-list d-lg-flex d-none mt-lg-2 mt-xl-0 mt-0">
                        @if ($data['loancal'])
                            @if (Auth::id())
                                <a href="{{ route('home') }}" class="btn btn-secondary btn-hover btn-hover-animate fw-bold fs-6">
                                    Logout
                                </a>
                            @else
                                <a href="{{ route('home') }}" class="btn btn-secondary btn-hover btn-hover-animate fw-bold fs-6">
                                    Login
                                </a>
                            @endif
                        @else
                            <a href="{{ route('application', ['amount' => '2000', 'duration' => '12']) }}"
                                class="btn btn-secondary btn-hover btn-hover-animate-right">
                                Apply Loan
                            </a>
                        @endif
                    </div>
                </div>
            </nav>
            <!-- End::nav -->

        </div>
        <!-- End::main-sidebar -->
    </div>

</aside>
<!-- End::app-sidebar -->
