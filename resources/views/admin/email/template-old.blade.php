<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>

    <style>
        @font-face {
            font-family: 'SF Pro Display';
            src: url('https://fonts.cdnfonts.com/css/sf-pro-display');
        }

        body {
            font-family: 'SF Pro Display', sans-serif !important;
        }

        .brand-color {
            color: #2b6cb0;
        }

        .footer-icon {
            color: #2b6cb0;
        }

        .content-box {
            background-color: #f7fafc;
            border-radius: 8px;
            padding: 2rem;
            max-width: 680px;
            width: 100%;
        }

        .email-content {
            padding-left: 30px;
            padding-right: 30px;
        }

        h1 img {
            margin: 0 auto;
        }

        .businessname {
            color: #1c4181;
            font-size: 20px;
        }

        span {
            color: #b2b2b2;
        }

        p {
            font-size: 15px;
            color: #000;
        }

        .small-logo {
            background-image: url('https://cashfaster.com.au/images/logo-small.png');
            width: 100px;
            height: 100px;
            background-size: 100%;
            background-position: 10%;
            background-repeat: no-repeat;
        }

        .note {
            font-size: 13px;
            text-align: justify;
        }

        .bolder {
            font-weight: bolder;
        }

        a {
            color: #007bff;
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            margin: 0 auto;
        }

        ul li {
            margin-right: 20px;
            /* Adjust spacing between links */
        }

        ul li a {
            color: #007bff;
            font-size: 13px;
            text-decoration: none;
            transition: color 0.3s ease;
            /* Add a smooth color transition effect */
        }

        ul li:not(:last-child)::after {
            content: " | ";
            right: -10px;
            color: #007bff;
        }


        /* Hover effect for the links */
        ul li a:hover {
            color: #0056b3;
            /* Adjust the hover color as needed */
        }

        .fa-linkedin {
            color: #1c4181;
            font-size: 30px;
        }

        .rounded-lg {
            background-color: #003788 !important;
            border-radius: 0.5rem;
        }

        .justify-center {
            justify-content: center;
        }

        .items-center {
            align-items: center;
        }

        .mb-2 {
            margin-bottom: 0.5rem;
        }

        .flex {
            display: flex;
        }

        .bg-blue-700 {
            --tw-bg-opacity: 1;

        }

        .text-white {
            --tw-text-opacity: 1;

        }

        .text-sm {
            font-size: .875rem;
            line-height: 1.25rem;
        }

        .ml-5 {
            margin-left: 1.25rem;
        }
    </style>
</head>

<body style="background-color: #f7fafc;">
    <div class="content-box"
        style="font-family: 'SF Pro Display', sans-serif; margin: 20px auto; max-width: 680px; background-color: #ffffff; padding: 20px; border-radius: 8px;">
        <div class="email-content" style="padding: 0 30px;">
            <h1 class="text-3xl font-bold brand-color mb-8" style="text-align:center">
                <img src="https://app.cashfaster.com.au/assets/images/logoBlue.png" width="250" alt="Logo"
                    style="margin:0 auto;">
            </h1>
            <div style="padding:10px">
                {!! $body !!}
            </div>
            <div style="padding:10px">
                <p class="my-4">Best regards,</p>
                <p><strong class="businessname">Cashfaster Australia</strong><br>
                    Support Team | <a href="cashfaster.com.au" style="color: #007bff;">cashfaster.com.au</a></p>
            </div>
            <hr class="mt-8" />
            <div class="mt-8 flex items-center">
                <div class="bg-blue-700 text-white rounded-lg flex items-center justify-center mb-2 small-logo"></div>
                <p class="text-sm ml-5 "><strong>E.</strong> contract@cashfaster.com.au<br />
                    <strong>A.</strong> <span>Cashfaster Pty Ltd, Melbourne, Australia</span><br />
                    <a href="https://www.linkedin.com/company/business-name" target="_blank"
                        style="color: #007bff;"><img src="https://app.cashfaster.com.au/assets/images/icon/linkedin.png"
                            width="30" alt="Logo" style="margin:10px auto;"></a>
                </p>
            </div>
            <div style="align-items: center; display: flex; margin-top: 2rem;">
                <p class="text-sm note"><span><i>Please note that the information in this email and any attachments is
                            confidential and intended only
                            for the individual named as the recipient. If you have received this email in error, please
                            notify
                            us immediately and delete it from your system. This email does not create a legally binding
                            agreement, unless expressly stated otherwise. All rights reserved Cashfaster Pty
                            Ltd.</i></span></p>
            </div>
            <div style="align-items: center;display: flex;margin-top: 2rem;">
                <ul style="margin: 0 auto;">
                    <li><a href="https://cashfaster.com.au/terms-conditions/" style="color: #007bff;">Terms &
                            Conditions</a></li>
                    <li><a href="https://cashfaster.com.au/privacy-policy/" style="color: #007bff;">Privacy Policy</a>
                    </li>
                    <li><a href="https://cashfaster.com.au/credit-guide/" style="color: #007bff;">Credit Guide</a></li>
                </ul>
            </div>
        </div>
    </div>
</body>

</html>
