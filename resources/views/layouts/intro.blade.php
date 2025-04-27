<html xmlns="http://www.w3.org/1999/xhtml" lang="es">

<head>
    <meta charset="utf-8" />
    <title>
        @yield('title')
    </title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap"
        rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body id="start" style="padding: 0%; display: grid; grid-template-columns: 1fr 1.3fr;">

    <div id="left_container">
        <div>
            <div id="overdub_1">Overdubs</div>
            <div id="overdub_2">Overdubs</div>
            <div id="overdub_3">Overdubs</div>
            <div id="overdub_4">Overdubs</div>
            <div id="overdub_5">Overdubs</div>
        </div>

        <img src="https://mirador-records.com/cdn/shop/files/IMG_2460.jpg?v=1706833771&width=3840"
            style="height: 100%;">

    </div>

    <div id="right_container">

        @yield('content')

    </div>

    <footer id="login_footer">
        Felipe Muñiz Peña - 2025 - PDAW
    </footer>

</body>

</html>