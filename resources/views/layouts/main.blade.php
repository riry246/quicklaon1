<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="horizontal" data-nav-style="menu-click" data-menu-position="fixed"
    data-theme-mode="light">

<head>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Quick Cash Loans from $300 - $2000 - CashFaster</title>
    <meta name="description"
        content="Apply for a quick cash loan up to $2,000 tailored to your needs. Secure your financial future today with CashFaster.">
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
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-11123653084"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'AW-11123653084');
    </script>

    @include('includes.stylesheet')

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCPTTAD4_PsEOlEVn40SV98kaeebqzMRW8&libraries=places">
    </script>

    <!-- Hotjar Tracking Code for my site -->
    <script>
        (function(h, o, t, j, a, r) {
            h.hj = h.hj || function() {
                (h.hj.q = h.hj.q || []).push(arguments)
            };
            h._hjSettings = {
                hjid: 3843794,
                hjsv: 6
            };
            a = o.getElementsByTagName('head')[0];
            r = o.createElement('script');
            r.async = 1;
            r.src = t + h._hjSettings.hjid + j + h._hjSettings.hjsv;
            a.appendChild(r);
        })(window, document, 'https://static.hotjar.com/c/hotjar-', '.js?sv=');
    </script>

    @vite(['resources/js/app.js'])
</head>

<body class="landing-body jobs-landing smp-0" id="app">
    <div class="landing-page-wrapper">
        <div class="main-content landing-main">
            @yield('content')
        </div>
        @include('frontend/widgets/footer')
</body>
<script>
    window.onload = function() {
        var leadID = getParameterByName('lead');
        if (leadID) {
            var button = document.getElementById('applybtn2');
            button.click();
        } else if (window.location.href.endsWith('/apply')) {
            var button = document.getElementById('applybtn');
            if (button) {
                button.click();
            }

        }




        function getParameterByName(name, url) {
            if (!url) url = window.location.href;
            name = name.replace(/[\[\]]/g, "\\$&");
            var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, " "));
        }
    };
</script>

</html>
