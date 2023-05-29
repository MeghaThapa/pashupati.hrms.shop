<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>@lang('PDF View')</title>
    <!-- Google font -->
    <link href="https://fonts.googleapis.com/css?family=Sen:400,700,800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Sen', sans-serif;
            font-size: .90em;
            line-height: 1.2;
        }

        .table-listing {
            font-family: 'Sen', sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        .table-listing td,
        .table-listing th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .table-listing tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table-listing tr:hover {
            background-color: #ddd;
        }

        .table-listing th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #04AA6D;
            color: white;
        }

        .table-image-preview {
            width: 50px;
            height: 50px;
            border-radius: 100%;
        }

        .no-preview {
            width: 50px;
            height: 50px;
            border-radius: 100%;
            background: #ddd;
            display: block;
            padding: 0;
            margin-top: -5px;
        }

    </style>
    @yield('page-style')
</head>

<body>
    <div class="container">
        <div class="row">
            @yield('content-area')
        </div>
    </div>
</body>

</html>
