@extends('layouts.intro')

@section('title', 'Overdubs')

@section('content')

    <div id="login_box" class="static_card">
        <h1>Forgot your password?</h1>
        <div>
            No problem. Just let us know your email address and we will email you a password reset link that will allow you
            to choose a new one.
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email:')" /><br>
                <x-text-input id="email" class="input_text" type="email" name="email" :value="old('email')" required
                    autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="button_1">
                    {{ __('Email Password Reset Link') }}
                </x-primary-button>
            </div>
        </form>
        <br>
        <a href="/">Go back.</a>
    </div>

@endsection