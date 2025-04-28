<html xmlns="https://www.w3.org/1999/xhtml" lang="es">


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

<body>
    <nav>
        <div><a href={{ route('dashboard') }}>Overdubs</a></div>

        <form method="GET" action="{{ route('search') }}" id="search_bar">
            @csrf
            <input type="text" name="search" size="70">
            <input type="submit" value="Search" class="link_button round_button"
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
                    <a href={{ route('dashboard') }}>Admin panel</a>
                    <a href={{ route('show_profile') }}>Profile</a>
                    <a href={{ route('insert_album') }}>Insert album</a>
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0px">
                        @csrf
                        <input class="round_button" type="submit" value="Log out">
                    </form>
                </div>
            @endif
        </div>

        <div>
            <span class="el_punto"></span>
        </div>

        <div id="report_form">
            <form method="POST" action="{{ route('send_report') }}">
                @csrf
                ¿There's something you want us to know? Fill the box below with your ideas, reports or suggestions.
                Thank you so much!
                <input type="hidden" name="receiver_id" value="">
                <textarea name="message" placeholder="Write your message." maxlength="300" rows="7"
                    style="padding: 1vh; width: 100%;"></textarea>
                <a style="float:left; margin-top: 1vh;" class="link_button" id="close">Close</a>

                <input style="float:right; margin-top: 1vh;" type="submit" value="Send">
            </form>
        </div>
    </nav>
    <!-- </header> -->

    @yield('content')

    <footer>

        <span>Overdubs has been coded by Felipe Muñiz Peña. <a id="send_message_link">Send us a message, notification,
                or report.</a></span>
    </footer>


    <script>
        let send_message_link = document.getElementById('send_message_link');
        let report_form = document.getElementById('report_form');
        let close = document.getElementById('close');

        send_message_link.onclick = function () {
            if (report_form.style.opacity = '0') {
                report_form.style.visibility = 'visible';
                report_form.style.opacity = '1';
            }
        }

        close.onclick = function () {
            report_form.style.display = 'none';
        }

        $(document).ready(function () {

            setInterval(() => {
                $(".el_punto").css("background-color", "lime");
                setTimeout(() => {
                    $(".el_punto").css("background-color", "white");
                }, 300)
                setTimeout(() => {
                    $(".el_punto").css("background-color", "yellowgreen");
                }, 600)
                setTimeout(() => {
                    $(".el_punto").css("background-color", "white");
                }, 1000)
                setTimeout(() => {
                    $(".el_punto").css("background-color", "lime");
                }, 1400)
            }, 3000)


        });
    </script>
</body>

</html>