<html>

<head>
    <title> CashFaster Customer Password Reset </title>
    <style>
        .button {
            background-color: #332d2d;
            border: none;
            border-radius: 5px;
            color: #ffffff !important;
            box-shadow: 0 4px 9px -4px #332d2d;
            padding: 8px 5px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 15px;
            margin: 4px 2px;
            cursor: pointer;
        }
    </style>
</head>

<body style="border: 5px solid #cccccc">
    <br>
    <div class="container " style="margin-left:30px; text-align: left;">
        Dear {{ $userName }},<br>
        Your password has been reset. Below are your system generated credentials, <br>
        <label>Password</label>: {{ $password }}
        <p><a href="{{ url('/') }}" class="button">Login To Your Account</a></p>

    </div>
</body>

</html>
