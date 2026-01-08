<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>EFKTP</title>
    <style>
        *,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: Arial, Helvetica, sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-size: 20px;
            margin: 0px;
        }

        @page {
            margin-top: 5px;
            margin-right: 5px;
            margin-left: 5px;
            margin-bottom: 5px;
        }

        p {
            margin: 0px;
        }

        .header-text {
            font-size: 15px;
            font-weight: bold;
        }

        .table-print,
        .table-print tr {
            border: 1px solid #000;
            border-collapse: collapse;
            padding: 5px;
            vertical-align: top;
        }

        .borderless {
            border: none
        }

        .table-print td {
            padding: 5px;
            font-size: 11px;
        }

        .table-print th {
            text-align: left;
            font-size: 11px;
            padding: 5px;
        }

        .border {
            border: 1px solid #000;
            border-collapse: collapse;

        }

        .page-break {
            page-break-after: always;
        }

        .table-border,
        .table-border th,
        .table-border td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 3px;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .text-start {
            text-align: right;
        }
    </style>

</head>

<body>
    @yield('content')
</body>

</html>
