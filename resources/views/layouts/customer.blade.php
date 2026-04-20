<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light"
    data-menu-styles="dark" data-toggled="close">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=no'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Quick Cash Loans from $300 - $2000 - CashFaster</title>
    <meta name="description" content="Apply for a quick cash loan up to $2,000 tailored to your needs. Secure your financial future today with CashFaster.">
    <meta name="Author" content="Frenzyard">
    <meta name="keywords" content="">
<!-- Favicon -->
   
   
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/assets/images/brand-logos/fav/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/assets/images/brand-logos/fav/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/assets/images/brand-logos/fav/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('/assets/images/brand-logos/fav/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('/assets/images/brand-logos/fav/safari-pinned-tab.svg') }}" color="#003788">
    <meta name="msapplication-TileColor" content="#003788">
    <meta name="theme-color" content="#003788">

    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-11123653084"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-11123653084');
</script>


    @include('includes.stylesheet')
    <!-- Hotjar Tracking Code for my site -->
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:3843794,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script>
  
    @vite(['resources/js/app.js'])
</head>

<body>
    <div class=" customer-dashboard" id="app">
   
        @include('frontend/user/widgets/header')
        <div class="container-fluid pt-5 mt-5 bg-secondary">
            <div class="main-content mx-auto m-5 text-white" style="width:90%;">
                <h2 class="landing-banner-heading "><span class="fw-bold">{{ Auth::user()->first_name }}</span>
                    {{ Auth::user()->last_name }}</h2>
                <p class="fs-6">Welcome - {{ now()->format('F jS, Y') }}</p>
            </div>
        </div>
        <div class="main-content mx-auto mt-2" style="width:90%;">
            <div class="container-fluid pt-5 smp-0">
                @yield('content')
            </div>
        </div>
        @include('frontend/widgets/footer')

</html>
