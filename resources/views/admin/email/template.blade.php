<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $subject }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=SF+Pro+Display:wght@400;700&display=swap" />

    <style>
        @font-face {
            font-family: 'SF Pro Display';
            src: url('https://fonts.cdnfonts.com/css/sf-pro-display');
        }

        @media (prefers-color-scheme: dark) {
            body {
                background-color: #121a26;
            }
        }

        @media (prefers-color-scheme: light),
        (prefers-color-scheme: no-preference) {
            body {
                background-color: #ffffff;
            }
        }

        * {
            font-family: 'SF Pro Display', sans-serif !important;
        }

        .main-container {
            overflow: hidden;
        }

        .main-container,
        .main-container * {
            box-sizing: border-box;
        }

        input,
        select,
        textarea,
        button {
            outline: 0;
        }

        .main-container {
            width: 640px;
            margin: 0 auto;
            background: #121a26 !important;
            overflow: hidden;
        }

        .background {
            width: 95%;
            background: #1f2b3c;
            margin: 20px auto;
            padding: 5px 0;
        }

        .flex-row-d {
            position: relative;
            width: 90%;
            height: 39.06px;
            margin: 22px auto;
            z-index: 17;
        }

        .small-logo {
            position: absolute;
            width: 100px;
            height: 29px;
            margin: 20px 0;
            background: url('https://app.cashfaster.com.au/assets/images/logo.png') no-repeat center;
            background-size: contain;
            z-index: 15;
        }

        .group {
            position: absolute;
            width: 16.93%;
            height: 52.73%;
            top: 47.27%;
            left: 83.07%;
            background: url(./assets/images/d8eb6ea6-5cf8-48f6-b0f3-c422c2a3a717.png) no-repeat center;
            background-size: 100% 100%;
            z-index: 17;
        }

        .vector {
            position: relative;
            width: 90%;
            height: 1px;
            margin: 15.44px auto;
            background-color: #121A26;
            z-index: 16;
        }

        .images {
            position: relative;
            width: 90%;
            ;
            height: 187px;
            margin: 22.5px auto;
            background: #2969FF url('https://app.cashfaster.com.au/assets/images/icon/refer.png') no-repeat center;
            background-size: 90%;
            background-position: 30% 20%;
            z-index: 19;
        }

        .flex-row-a {
            position: relative;
            width: 90%;
            margin: 30px auto;
            z-index: 18;
        }

        h1 {
            display: flex;
            align-items: flex-start;
            justify-content: flex-start;
            width: 544px;
            top: 0;
            left: 0;
            color: #ffffff;

            font-size: 24px;
            font-weight: 700;
            line-height: 33.6px;
            text-align: left;
            text-overflow: initial;
            z-index: 9;
            overflow: hidden;
        }

        p {
            position: relative;
            color: #e2e8f0;

            font-size: 16px;
            font-weight: 400;
            line-height: 24px;
            text-align: left;
            letter-spacing: 0.2px;
        }

        a {
            position: relative;
            color: #2969ff;

            font-size: 16px;
            font-weight: 400;
            line-height: 24px;
            text-align: left;
            letter-spacing: 0.2px;
        }



        .button-primary {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: nowrap;
            gap: 10px;
            position: relative;
            width: fit-content;
            height: 64px;
            padding: 20px 45px 20px 45px;
            cursor: pointer;
            background: #2969ff;
            border: none;
            z-index: 13;

            color: #ffffff !important;
            text-decoration: none;
        }

        span {
            display: block;
            position: relative;
            color: #94a3b8;

            font-size: 16px;
            font-weight: 400;
            line-height: 24px;
            text-align: left;
            letter-spacing: 0.2px;
            z-index: 2;
            margin-top: 20px;
        }

        .download-icon {
            display: flex;
            align-items: flex-start;
            flex-wrap: nowrap;
            gap: 10px;
            position: relative;
            width: 242px;
            margin: 24px 0 0 32px;
            z-index: 3;
        }

        .logo-google-play-badge {
            flex-shrink: 0;
            position: relative;
            width: 107px;
            height: 32px;
            z-index: 4;
        }

        .google-play-badge {
            position: absolute;
            width: 100.4%;
            height: 100%;
            top: 0;
            left: 0;
            cursor: pointer;
            background: transparent;
            background: url('https://app.cashfaster.com.au/assets/images/icon/google-play-badge.png') no-repeat center;
            background-size: 100% 100%;
            border: none;
            z-index: 5;
        }

        .logo-app-store {
            flex-shrink: 0;
            position: relative;
            width: 125px;
            height: 32px;
            cursor: pointer;
            background: transparent;
            border: none;
            z-index: 6;
        }

        .logo-app-store-5 {
            position: relative;
            width: 124.284px;
            height: 32px;
            margin: 0 0 0 0;
            background: url('https://app.cashfaster.com.au/assets/images/icon/app-store.png') no-repeat center;
            background-size: cover;
            z-index: 7;
        }

        .questions-faq-contact {
            position: relative;
            width: 544px;
            margin: 32px;

            font-size: 16px;
            font-weight: 400;
            line-height: 24px;
            text-align: left;
            letter-spacing: 0.2px;
            z-index: 8;
            padding-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="main-container">
        <div class="background">
            <div class="flex-row-d">
                <div class="small-logo"></div>
                <div class="group"></div>
            </div>
            <div class="vector"></div>
            <div class="images"></div>
            <div class="flex-row-a">
                {!! $body !!}

                <p>Thanks in advance<br/> Team
                    CashFaster</p>
                
                <span style="display:none;">Cashfaster at the touch of a button!
                    Download our app for Google &
                    Mac.</span>
            </div>

            <div class="download-icon" style="display:none;">
                <div class="logo-google-play-badge">
                    <button class="google-play-badge"></button>
                </div>
                <button class="logo-app-store">
                    <div class="logo-app-store-5"></div>
                </button>
            </div>
            <div class="questions-faq-contact">
                <span>Questions or faq? Contact us at <a>contact@cashfaster.com.au.</a> If you'd rather not
                    receive this kind of email, Don’t want any more emails from Cashfaster? <a>Unsubscribe</a>.
                </span>
                <span>
                    Cashfaster pty ltd, Melbourne, Australia<br />© 2024 Cashfaster</span>
            </div>
        </div>
    </div>

</body>

</html>
