<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="horizontal" data-nav-style="menu-click" data-menu-position="fixed"
    data-theme-mode="light">

<head>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Quick Cash Loans from $300 - $2000 - Quickloans</title>
    <meta name="description"
        content="Apply for a quick cash loan up to $2,000 tailored to your needs. Secure your financial future today with Quickloans.">
    <meta name="Author" content="Frenzyard">
    <!-- Favicon -->

    <link rel="apple-touch-icon" sizes="180x180"
        href="{{ asset('/assets/images/brand-logos/fav/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ asset('/assets/images/brand-logos/fav/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ asset('/assets/images/brand-logos/fav/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('/assets/images/brand-logos/fav/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('/assets/images/brand-logos/fav/safari-pinned-tab.svg') }}" color="#003788">
    <meta name="msapplication-TileColor" content="#003788">
    <meta name="theme-color" content="#003788">
    <!-- Choices JS -->
    <script src="{{ asset('assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>

    <!-- Main Theme Js -->
    <script src="{{ asset('assets/js/main.js') }}"></script>


    @include('includes.stylesheet')

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCPTTAD4_PsEOlEVn40SV98kaeebqzMRW8&libraries=places">
    </script>


    @vite(['resources/js/app.js'])
</head>

<body class="landing-body jobs-landing smp-0" id="app">
    <div class="landing-page-wrapper">
        @include('frontend/widgets/header')
        @include('frontend/widgets/sidebar')
        <div class="main-content landing-main">
            @yield('content')
        </div>
        @include('frontend/widgets/footer')
        <div class="scrollToTop">
            <span class="arrow"><i class="ri-arrow-up-s-fill fs-20"></i></span>
        </div>
        <div id="responsive-overlay"></div>

</body>

</html>