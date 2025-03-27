@extends('layouts.intro')

@section('titulo', 'Inicio')

@section('contenido')


    <div id="login_box" class="static_card">
        <h1 style="margin-bottom: 1.5vh;">Welcome.</h1>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <div>
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}">Inicio</a><br><br>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <input type="submit" class="button_1" value="Log out">
                    </form>


                @else
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <!-- <x-input-label for="email" value="Email:" /><br> -->
                            <x-text-input id="email" type="email" name="email" :value="old('email')" required
                                autocomplete="username" placeholder="Email or username" class="input_text" style="width: 60%" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div>
                            <!-- <x-input-label for="password" :value="__('Password:')" /><br> -->

                            <x-text-input id="password" type="password" name="password" required autocomplete="current-password"
                                placeholder="Password" class="input_text" style="width: 60%" />

                            <x-input-error :messages="$errors->get('password')" />
                        </div>

                        <!-- Remember Me -->
                        <div>
                            <label for="remember_me">
                                <input id="remember_me" type="checkbox"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                                <span>
                                    {{ __('Remember me') }}
                                </span>
                            </label>
                        </div>

                        <div>
                            <x-primary-button class="link_button round_button">
                                {{ __('Log in') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <div style="margin-top: 2vh;">
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                        <br>

                        @if (Route::has('register'))
                            <a style="margin-top: 5vh;" href="{{ route('register') }}">Create an account.</a>
                        @endif
                    </div>
                @endauth
            @endif
        </div>
    </div>
@endsection