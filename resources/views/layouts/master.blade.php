<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-vertical-style="overlay" data-theme-mode="dark"
    data-header-styles="dark" data-menu-styles="dark" data-toggled="close">

<head>
    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> CashFaster - Admin</title>
    <meta name="Description" content="">
    <meta name="Author" content="Spruko Technologies Private Limited">
    <meta name="keywords" content="">

    <!-- Favicon -->
  
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/assets/images/brand-logos/fav/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/assets/images/brand-logos/fav/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/assets/images/brand-logos/fav/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('/assets/images/brand-logos/fav/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('/assets/images/brand-logos/fav/safari-pinned-tab.svg') }}" color="#003788">
    <meta name="msapplication-TileColor" content="#003788">
    <meta name="theme-color" content="#003788">

    <!-- Main Theme Js -->
    <script src="{{asset('assets/js/authentication-main.js')}}"></script>

    @include('includes.stylesheet')

</head>

<body class="bg-white">
    @yield('content')
    @include('includes.javascript')
    




</body>
