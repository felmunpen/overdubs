<html xmlns="http://www.w3.org/1999/xhtml" lang="es">

<head>
    <meta charset="utf-8" />
    <title>
        @yield('titulo') - Ej7 Laravel
    </title>
    <link rel="stylesheet" href="{{ asset('css/estilos.css') }}" />
</head>

<body>
    <h1>Ejercicio 7</h1>

    <nav>
        SOS ALTO ADMIN, SABELO
    </nav>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <input type="submit" class="ms-3" value="Log out">
    </form>

    @yield('contenido')

    <footer>
        ADMIN FOOTER -
        Felipe Muñiz Peña - DWES - Tarea 5.
    </footer>

</body>

</html>