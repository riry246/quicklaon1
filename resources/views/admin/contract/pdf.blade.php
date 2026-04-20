<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CashFasterAUCredit#{{ $contract->contract_id }}</title>
    <style>
        @font-face {
            font-family: 'Freehand';
            src: url({{ Storage::url('public/fonts/Freehand.ttf') }}) format("truetype");
            font-weight: 400; // use the matching font-weight here ( 100, 200, 300, 400, etc).
            font-style: normal; // use the matching font-style here
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            /* Set margins to zero */
            padding: 0;
            /* Set padding to zero */
        }

        p {
            font-size: 15px;
        }

        table {
            background-color: rgba(241, 241, 241, 0.5) !important;
            border-collapse: collapse;
            width: 100% !important;
        }

        table,
        td {
            padding: 5px;
        }

        .signature {
            font-size: 22px;
            font-family: 'Freehand', cursive;
        }
    </style>
</head>

<body>
    {!! $document !!}
    @include('admin.contract.certificate')
</body>

</html>
