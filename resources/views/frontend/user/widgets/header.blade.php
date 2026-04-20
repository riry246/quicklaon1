<!-- app-header -->
<header class="app-header" style="padding:0">
    <!-- Start::main-header-container -->
    <div class="main-header-container container-fluid customer-header bg-secondary px-5">
        <!-- Start::header-content-left -->
        <div class="header-content-left">
            <!-- Start::header-element -->
            <div class="header-element">
                <div class="header-logo">
                    <a href="#" class="header-logo">
                        <a href="{{ route('user.login') }}" class="header-logo">
                            <img src="../assets/images/brand-logos/desktop-dark.png" alt="logo" width="200"
                                class="toggle-logo p-3">
                        </a>
                    </a>
                </div>
            </div>
            <!-- End::header-element -->
            <!-- Start::header-element -->
            <div class="header-element">
                <!-- Start::header-link -->

                <!-- End::header-link -->
            </div>
            <!-- End::header-element -->
        </div>
        <!-- End::header-content-left -->
        <!-- Start::header-content-right -->
        <div class="header-content-right">
            <!-- Start::header-element -->

            <!-- End::header-element -->
            <!-- Start::header-element -->
            @include('frontend.user.widgets.notification')
            <!-- End::header-element -->
            <!-- Start::header-element -->
            <div class="header-element">
                <!-- Start::header-link|dropdown-toggle -->
                <a href="#" class="header-link dropdown-toggle" id="mainHeaderProfile" data-bs-toggle="dropdown"
                    data-bs-auto-close="outside" aria-expanded="false">
                    <div class="d-flex align-items-center  border-start ps-3">
                        
                        <div class="d-sm-block d-none">
                            <p class="fw-medium mb-0 lh-1 text-white fs-17">{{ Auth::user()->first_name }}
                                {{ Auth::user()->last_name }}</p>
                            <span class="op-7 fw-normal d-block fs-13">{{ Auth::user()->email }}</span>
                        </div>
                        <div class="me-sm-2 me-0">
                            
                            <i class='bx bx-dots-horizontal-rounded fs-18 me-2 mx-3' ></i>
                        </div>
                    </div>
                </a>
                <!-- End::header-link|dropdown-toggle -->
                <ul class="main-header-dropdown dropdown-menu pt-0 overflow-hidden header-profile-dropdown dropdown-menu-end"
                    aria-labelledby="mainHeaderProfile">

                    <li><a class="dropdown-item d-flex chat-button" href="javascript:void(0);"><i
                                class="ti ti-headset fs-18 me-2 op-7"></i>Support</a></li>
                    <li><a class="dropdown-item d-flex" href="{{ route('customer.logout') }}"><i
                                class="ti ti-logout fs-18 me-2 op-7"></i>Log Out</a></li>
                </ul>
            </div>
            <!-- End::header-element -->
        </div>
        <!-- End::header-content-right -->
    </div>
    <!-- End::main-header-container -->
</header>
<!-- /app-header -->
