<!-- app-header -->
<header class="app-header mx-2">

    <!-- Start::main-header-container -->
    <div class="main-header-container container-fluid border-bottom ">

        <!-- Start::header-content-left -->
        <div class="header-content-left">

            <!-- Start::header-element -->
            <div class="header-element">
                <div class="horizontal-logo">
                    <a href="{{ route('dashboard')}}" class="header-logo">
                        <img src="../assets/images/brand-logos/desktop-logo.png" alt="logo" class="desktop-logo">
                        <img src="../assets/images/brand-logos/toggle-logo.png" alt="logo" class="toggle-logo">
                        <img src="../assets/images/brand-logos/desktop-dark.png" alt="logo" class="desktop-dark">
                        <img src="../assets/images/brand-logos/toggle-white.png" alt="logo" class="toggle-dark">
                        <img src="../assets/images/brand-logos/desktop-white.png" alt="logo" class="desktop-white">
                        <img src="../assets/images/brand-logos/toggle-white.png" alt="logo" class="toggle-white">
                    </a>
                </div>
            </div>
            <!-- End::header-element -->

            <!-- Start::header-element -->
            <div class="header-element">
                <!-- Start::header-link -->
                <a aria-label="Hide Sidebar"
                    class="sidemenu-toggle header-link animated-arrow hor-toggle horizontal-navtoggle"
                    data-bs-toggle="sidebar" href="javascript:void(0);"><span></span></a>
                <!-- End::header-link -->
            </div>
            <!-- End::header-element -->

        </div>
        <!-- End::header-content-left -->

        <!-- Start::header-content-right -->
        <div class="header-content-right">

            <!-- Start::header-element -->
            <div class="header-element header-search">
                <!-- Start::header-link -->
                <a href="javascript:void(0);" class="header-link" data-bs-toggle="modal" data-bs-target="#searchModal">
                    <i class="bx bx-search-alt-2 header-link-icon"></i>
                </a>
                <!-- End::header-link -->
            </div>
            <!-- End::header-element -->
            <!-- Start::header-element -->

            <!-- End::header-element -->



            <!-- Start::header-element -->
            @include('admin.widgets.notification')
            <!-- End::header-element -->

            <!-- Start::header-element -->
            <div class="header-element">
                <!-- Start::header-link|dropdown-toggle -->
                <a href="#" class="header-link dropdown-toggle" id="mainHeaderProfile" data-bs-toggle="dropdown"
                    data-bs-auto-close="outside" aria-expanded="false">
                    <div class="d-flex align-items-center  border-start ps-3">

                        <div class="d-sm-block d-none">
                            <p class="fw-medium mb-0 lh-1 text-dark fs-15">{{ Auth::user()->first_name }}
                                {{ Auth::user()->last_name }}</p>
                            <span class="op-7 fw-normal d-block fs-13">{{ Auth::user()->email }}</span>
                        </div>
                        <div class="me-sm-2 me-0">

                            <i class='bx bx-dots-horizontal-rounded fs-18 me-2 mx-3'></i>
                        </div>
                    </div>
                </a>
                <!-- End::header-link|dropdown-toggle -->
                <ul class="main-header-dropdown dropdown-menu pt-0 overflow-hidden header-profile-dropdown dropdown-menu-end"
                    aria-labelledby="mainHeaderProfile">
                    <li><a class="dropdown-item d-flex" href="{{ route('logout') }}"><i
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
