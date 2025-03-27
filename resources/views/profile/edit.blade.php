@extends('layouts.common')

@section('titulo', 'felmunpen')

@section('contenido')

    <main>
        <div>
            @include('profile.partials.update-profile-information-form')
        </div>
        <div>
            @include('profile.partials.update-password-form')
        </div>
        <div>
            @include('profile.partials.delete-user-form')
        </div>
    </main>
@endsection