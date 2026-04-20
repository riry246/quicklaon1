<!DOCTYPE html>
<html>
<head>
    <style>
        /* Define your CSS styles here */
        .email-body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="email-body">
        <h1>Verify Your Email Address</h1>
        <p>Hello, {{ $user->first_name }}!</p>
        <p>Click the button below to verify your email address:</p>
        <a href="{{ $verificationLink }}" style="background-color: #3490dc; color: #ffffff; padding: 10px 20px; text-decoration: none; display: inline-block; border-radius: 4px;">Verify Email</a>
        <p>If you did not create an account, you can safely ignore this email.</p>
    </div>
</body>
</html>
