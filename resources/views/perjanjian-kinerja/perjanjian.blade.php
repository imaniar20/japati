<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }
        }

        @page {
          size: A4 portait;
          margin: 1cm 1cm 2cm 1cm;
        }

        html {
            height: 0;
        }

        body {
            font-family: Arial, sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin: 0;
        }

        .border-side {
            border: 1px solid #000;
            padding: 90px 10px 0px 10px;
        }

        .garuda {
            width: 100px;
            margin-bottom: 10px;
        }

        .text-center {
            text-align: center;
        }

        .title,
        .title-2 {
            font-family: 'Calibri Light', sans-serif;
            font-size: 14pt;
            font-weight: bold;
            text-align: center;
        }

        /*.title-2 {
            font-size: 12pt;
        }*/

        .h-50 {
            height: 50pt;
        }
        
        .h-30 {
            height: 30pt;
        }

        .inner-table {
            width: auto;
            margin-left: auto;
            margin-right: auto;
            font-size: 11pt;
        }

        .inner-table td {
            padding: 5px;
        }

        .table {
            font-family: 'Times New Roman', serif;
            font-size: 10pt;
        }

        .table-fs-11 {
            font-family: 'Times New Roman', serif;
            font-size: 11pt;
        }

        .table-fs-9 {
            font-family: 'Times New Roman', serif;
            font-size: 9pt;
        }

        .title2 {
            font-family: 'Times New Roman', serif;
            font-size: 11pt;
            text-align: center;
        }

        .tablehead-bg {
            background-color: #D9E2F3 !important;
        }

        .tablehead {
            font-weight: bold;
            text-align: center;
        }

        .tablehead-left {
            font-weight: bold;
            text-align: left;
        }

        .pl-3 {
            padding-left: 3px;
        }

        .text-top {
            vertical-align: top;
        }

        .text-bottom {
            vertical-align: bottom;
        }

        .text-left {
            text-align: left;
        }

        p {
            margin: 0
        }

        .page-break-after {
            page-break-after: always;
        }

        .italic {
            font-style: italic;
        }

        body#data-skp table:nth-child(2) td,
        body#realisasi-skp table:nth-child(2) td {
            padding-left: 3px;
        }

        tbody,
        .break-inside-avoid {
            page-break-inside: avoid !important;
        }

        .vh-100 {
            height: 100vh;
        }

        #data-skp table td {
            padding-left: 3px;
        }
    </style>
</head>

<body>

    {{-- Pendahuluan --}}
    @include('perjanjian-kinerja.pendahuluan')

    {{-- SKP --}}
    @include('perjanjian-kinerja.skp')

</body>

</html>
