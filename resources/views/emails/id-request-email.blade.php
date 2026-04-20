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
        <h2>Loan Application Process - ID Verification Required</h2>
        <p>Dear {{ $user->first_name }},</p>
        <p>Thank you for choosing Cashfaster for your loan application. We are pleased to assist you in achieving your financial goals.</p>
        <p>We are in the process of reviewing your loan application, and part of our due diligence requires verifying your identity. To proceed with your application, we kindly request that you complete the ID verification process:</p>
        <p><strong>ID Verification:</strong> To comply with legal and security requirements, please login to and verify your Id card. Click the button below to begin the verification process:</p>
        <a class="cta-button" href="{{ $baseUrl }}">Verify My ID</a>
        <p>Rest assured, your information is handled securely and confidentially. We prioritize the privacy and security of our customers' data.</p>
        <p>If you have any questions or encounter any issues during the verification process, our dedicated support team is ready to assist. Reach out to us at support@cashfaster.com.au.</p>
        <p>Once your ID verification is complete, we will expedite the evaluation of your loan application and notify you of the status. Our team is committed to providing a prompt and efficient service.</p>
        <p>Thank you for choosing Cashfaster. We appreciate your trust and look forward to assisting you throughout the loan application process.</p>
        <p>Best regards,</p>
        
        <p>Cashfaster</p>
    </div>
</body>
</html>
