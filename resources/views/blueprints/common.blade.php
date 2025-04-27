<html xmlns="http://www.w3.org/1999/xhtml" lang="es">

<head>
    <meta charset="utf-8" />
    <title>
        @yield('title') - felmunpen_app
    </title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
</head>

<body>
    <h1>Ejercicio 7</h1>

    @if(Auth::user()->usertype === 'User')
        <nav>
            <div>SOS ALTO USER, SABELO. RECORDÁ QUIEN SOS:
                {{ Auth::user()->name }}
            </div>
            <div><a href={{ route('dashboard') }}>Home</a></div>
            <div><a href={{ route('insert_album') }}>Insert album</a></div>
        </nav>
    @elseif(Auth::user()->usertype === 'Artist')
        <nav>
            SOS ALTO ARTISTA DE LA PISTA, SABELO
            <div><a href={{ route('dashboard') }}>Home</a></div>
        </nav>
    @elseif(Auth::user()->usertype === 'Admin')
        <nav>
            ALÓ CAPITÁN
            <div><a href={{ route('dashboard') }}>Home</a></div>
        </nav>
    @endif

    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <!-- <x-primary-button class="ms-3">
            {{ __('Log out') }}
        </x-primary-button> -->

        <input type="submit" class="ms-3" value="Log out">

    </form>

    <form method="GET" action="{{ route('search') }}">
        @csrf
        <input type="text" name="search">
        <input type="submit" value="Buscar">
    </form>

    @yield('content')

    <footer>
        USER FOOTER -
        Felipe Muñiz Peña - DWES - Tarea 5.
    </footer>

</body>

</html>