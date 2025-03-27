@extends('layouts.common')

@section('titulo', 'Inicio')

@section('contenido')

    <main>
        <div>
            <h2><a href="{{ url('/dashboard') }}">Admin Control Panel</a></h2>
            <div
                style="display: grid; grid-template-columns: 1fr 1fr; grid-template-rows: 1fr 1fr; column-gap: 2vh; row-gap: 2vh;">
                <div class="admin_card card medium"><a href="{{ route('show_users') }}">Users</a></div>
                <div class="admin_card card medium"><a href="{{ route('show_albums') }}">Albums</a></div>
                <div class="admin_card card medium"><a href="{{ route('show_artists') }}">Artists</a></div>
                <div class="admin_card card medium"><a href="{{ route('show_reviews') }}">Reviews</a></div>
                <div class="admin_card card medium" style="grid-column: 1/-1"><a href="{{ route('data_report') }}">Data
                        Report</a>
                </div>
            </div>

        </div>
    </main>

@endsection