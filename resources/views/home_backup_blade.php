<html xmlns="http://www.w3.org/1999/xhtml" lang="es">

<head>
    <meta charset="utf-8" />
    <title>
        @yield('titulo') Home
    </title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>


    <!-- <nav>
        SOS ALTO ADMIN, SABELO
    </nav>

    @yield('contenido') -->

    <div class="center_card">
        <h2>Home</h2>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <div>
            @if (Route::has('login'))
            <!-- <nav> -->
            @auth
            <a href="{{ url('/dashboard') }}">Inicio</a><br><br>
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <!-- <x-primary-button class="ms-3">{{ __('Log out') }}</x-primary-button> -->

                <input type="submit" class="ms-3 button_1" value="Log out">

            </form>


            @else
            <!-- <a href="{{ route('login') }}">Log in</a><br><br> -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email:')" /><br>
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                        required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password:')" /><br>

                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                        autocomplete="current-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                            name="remember">
                        <span class="ms-2 text-sm text-gray-600">
                            {{ __('Remember me') }}
                        </span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-primary-button class="button_1">
                        {{ __('Log in') }}
                    </x-primary-button>

                    @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        href="{{ route('password.request') }}" style="font-size:10pt">
                        {{ __('Forgot your password?') }}
                    </a>
                    @endif


                </div>
            </form>
            @if (Route::has('register'))
            <a href="{{ route('register') }}" class="button_1">Register</a><br><br>
            @endif
            @endauth
            <!-- </nav> -->
            @endif
        </div>

    </div>

    <footer>
        home footer
    </footer>

</body>

</html>