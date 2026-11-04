<!DOCTYPE html>
<html>
    <head>
        <title>Be right back.</title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- The fonts of the website, from our own server --}}
        @vite('resources/assets/css/vendor.css')

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #14213d;
                background: #fafafa;
                display: table;
                font-weight: 600;
                font-family: 'Space Grotesk Variable', 'Space Grotesk', sans-serif;
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 56px;
                margin-bottom: 40px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">Be right back.</div>
            </div>
        </div>
    </body>
</html>
