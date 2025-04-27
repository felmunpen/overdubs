@extends('layouts.common')

@section('title', 'Overdubs')

@section('content')

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