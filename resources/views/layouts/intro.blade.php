<html xmlns="http://www.w3.org/1999/xhtml" lang="es">

<head>
    <meta charset="utf-8" />
    <title>
        @yield('titulo')
    </title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body id="start" style="padding: 0%; display: grid; grid-template-columns: 1fr 1fr;">

    <div id="left_container">
        <span style="color: white;">The<br>Equalizer</span>

        <img src="https://bpb-us-e1.wpmucdn.com/www.thelantern.com/dist/c/1/files/2022/11/Record-Stores.jpg">

    </div>

    <div id="right_container">

        @yield('contenido')

    </div>

    <!-- <footer>
        USER FOOTER -
        Felipe Muñiz Peña - DWES - Tarea 5.
    </footer> -->

    <div id="login_footer">
        Felipe Muñiz Peña - 2025 - PDAW
    </div>

</body>

</html>