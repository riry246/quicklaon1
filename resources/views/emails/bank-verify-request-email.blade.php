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

        .cta-button {
            display: inline-block;
            background-color: #007BFF;
            color: #ffffff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="email-content">
        <h2>Bank Verification Required for Loan Application</h2>
        <p>Dear {{ $user->first_name }},</p>
        <p>Thank you for choosing Cashfaster for your loan application. We appreciate your trust in our services.</p>
        <p>We are in the process of reviewing your loan application, and we require bank verification to proceed. Bank
            verification is an essential step to ensure a smooth and secure loan process.</p>
        <p>Click the button below to begin the bank verification process:</p>
        <a class="cta-button" href="{{ $baseUrl }}">Verify My Bank</a>
        <p>During the verification process, you will be asked to provide your banking information securely. We take data
            privacy and security seriously, and your information will be handled with the utmost care.</p>
        <p>If you have any questions or encounter any issues during the verification process, please don't hesitate to
            contact our customer support team at info@cashfaster.com.au. We are here to assist you throughout the
            application process.</p>
        <p>Once your bank verification is complete, our loan processing team will expedite the evaluation, and you will
            be notified of the status of your loan application shortly.</p>
        <p>Thank you for choosing Cashfaster. We look forward to assisting you in achieving your financial goals.</p>
        <p>Best regards,</p>
        <p>Cashfaster</p>
    </div>
</body>

</html>
