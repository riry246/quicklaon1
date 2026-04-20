<!DOCTYPE html>
<html>
<head>
    <style>
        /* Add your custom email styles here */
        body {
            font-family: Arial, sans-serif;
        }
        .email-content {
            background-color: #f4f4f4;
            padding: 20px;
        }
        .status-indicator {
            font-weight: bold;
            color: #007BFF;
        }
    </style>
</head>
<body>
    <div class="email-content">
        <h2>Loan Processing Update</h2>
        <p>Dear {{ $user->first_name }},</p>
        <p>We appreciate your application with Cashfaster. Here is an update on the status of your loan processing.</p>
        <p>Your loan application is currently in <span class="status-indicator">Processing </span> status.</p>
        <p>If additional information or documentation is required to move forward with your application, we will notify you promptly.</p>
        <p>For any questions or concerns related to your loan application, please don't hesitate to contact our customer support team at support@cashfaster.com.au. We are here to assist you throughout the application process.</p>
        <p>Thank you for choosing Cashfaster. We are dedicated to providing you with the best service and helping you achieve your financial goals.</p>
        <p>Best regards,</p>
        <p>Cashfaster</p>
    </div>
</body>
</html>
