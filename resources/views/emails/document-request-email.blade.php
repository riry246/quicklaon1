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
        <h2>Document Request for Your Loan Application</h2>
        <p>Dear {{ $user->first_name }},</p>
        <p>Thank you for choosing Cashfaster for your loan application. We appreciate your trust in our services.</p>
        <p>We are in the process of reviewing your loan application, and we require some additional documents to
            complete the evaluation. Please provide the following documents as soon as possible:</p>
        <ul>
            @foreach ($documentType as $d)
                <li>{{ $d }}</li>
            @endforeach
        </ul>
        <p>These documents are essential to assess your loan application fully and accurately. You can upload them
            through our secure online portal using the button below:</p>
        <a class="cta-button" href="{{$baseUrl}}">Upload Documents</a>
        <p>If you encounter any issues during the document submission process or have questions about the required
            documents, please don't hesitate to contact our customer support team at [Customer Support Email Address] or
            [Customer Support Phone Number]. We are here to assist you throughout the application process.</p>
        <p>Once we receive the necessary documents, our loan processing team will expedite the evaluation, and you will
            be notified of the status of your loan application shortly.</p>
        <p>Thank you for choosing Cashfaster, and we look forward to assisting you in achieving your financial
            goals.</p>
        <p>Best regards,</p>
        
        <p>Cashfaster</p>
    </div>
</body>

</html>
