@extends('layouts.common')

@section('title', 'Overdubs')

@section('content')

    <main>
        <div>
            <h2><a href="{{ url('/dashboard') }}">Admin Control Panel</a></h2>
            <div style="display: grid; grid-template-columns: 1fr 1fr; column-gap: 2vh;">
                <div style="display: grid; grid-template-rows: 1fr 1fr 1fr 1fr 1fr; row-gap: 2vh;">
                    <div class="admin_card card"><a href="{{ route('show_users') }}">Users</a></div>
                    <div class="admin_card card"><a href="{{ route('show_albums') }}">Albums</a></div>
                    <div class="admin_card card"><a href="{{ route('show_artists') }}">Artists</a></div>
                    <div class="admin_card card"><a href="{{ route('show_reviews') }}">Reviews</a></div>
                    <div class="admin_card card"><a href="{{ route('data_report') }}">Data
                            Report</a>
                    </div>
                </div>
                <div id="definitions" class="admin_card card" style="text-align: justify; font-size: 10pt;">
                    This is the main panel for administrators. You can navigate throught the diferents sections through the
                    links on the left.<br><br>
                    · Users: shows all the users in Overdubs. As an admin, you can see their non-sensible data, such as
                    passwords, send messages, or blocking them.<br><br>
                    · Albums: here you can see the albums in our database, consulting, edit and delete them.<br><br>
                    · Artists: contains all the artists included in Overdubs. From there, you can access to the artist
                    information and know which ones are registered as users.<br><br>
                    · Reviews: has information about all the reviews written by the users.<br><br>
                    · Data report: contains a report about the more relevant data gathered in the database, including plain
                    text and graphics.<br><br>
                </div>
            </div>

        </div>

    </main>

@endsection