<html xmlns="http://www.w3.org/1999/xhtml" lang="es">

<head>
    <meta charset="utf-8" />
    <title>
        @yield('titulo')
    </title>
    <link rel="stylesheet" href="https://overdubs.koyeb.app/public/css/style.css" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>
    <!-- <header> -->
    <nav>
        <div><a href={{ route('dashboard') }}>EQAPP</a></div>

        <form method="GET" action="{{ route('search') }}" id="search_bar">
            @csrf
            <input type="text" name="search" size="70">
            <input type="submit" value="Buscar" class="link_button round_button"
                style="padding-left: 1vh; padding-right: 1vh;">
            <input type="hidden" name="iterator" id="iterator" value="1">

        </form>

        <div class="dropdown">

            @if(Auth::user()->usertype === 'User')
                <a href="#" class="dropbtn">
                    {{ Auth::user()->name }}
                </a>
                <div class="dropdown-content">
                    <a href={{ route('show_profile') }}>Profile</a>
                    <a href={{ route('insert_album') }}>Insert album</a>
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0px">
                        @csrf
                        <input class="round_button" type="submit" value="Log out">
                    </form>
                </div>

            @elseif(Auth::user()->usertype === 'Artist')
                <a href="#" class="dropbtn">
                    {{ Auth::user()->name }}
                </a>
                <div class="dropdown-content">
                    <a href={{ route('show_profile') }}>Profile</a>
                    <a href={{ route('insert_album') }}>Insert album</a>
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0px">
                        @csrf
                        <input class="round_button" type="submit" value="Log out">
                    </form>
                </div>
            @elseif(Auth::user()->usertype === 'Admin')
                <a href="#" class="dropbtn">
                    {{ Auth::user()->name }}
                </a>
                <div class="dropdown-content">
                    <a href={{ route('dashboard') }}>Dashboard</a>
                    <a href={{ route('show_profile') }}>Profile</a>
                    <a href={{ route('insert_album') }}>Insert album</a>
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0px">
                        @csrf
                        <input class="round_button" type="submit" value="Log out">
                    </form>
                </div>
            @endif
        </div>
        <!-- <form method="POST" action="{{ route('logout') }}">
            @csrf
            <input type="submit" class="ms-3" value="Log out">
        </form> -->
    </nav>
    <!-- </header> -->

    @yield('contenido')

    <!-- <div id="about_this_project">
        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
        industry's
        standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to
        make
        a type specimen book. It has survived not only five centuries, but also the leap into electronic
        typesetting,
        remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets
        containing
        Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including
        versions
        of Lorem Ipsum.
    </div> -->
    <footer>

        <span>XXXX has been coded by Felipe Muñiz Peña. <a href="#">More about this project.</a></span>
    </footer>

</body>

</html>
