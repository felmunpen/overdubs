@extends('layouts.common')

@section('title', 'Overdubs')

@section('content')

    <main>
        <div>
            <h2>INSERT ALBUM</h2>

            <form method="POST" action="{{ route('inserted_album') }}">
                @csrf

                <div>
                    <label for="album_name">Album name:</label>
                    <input type="text" name="album_name" required>
                </div>

                <div>
                    <label for="artist_name">Artist name:</label>
                    <input type="text" name="artist_name" required>
                </div>

                <div>
                    <label for="release_year">Release year:</label>
                    <select name="release_year" id="release_year">
                        <?php for ($i = 1900; $i <= 2025; $i++): ?>
                        <option value="<?php    echo $i; ?>">
                            <?php    echo $i; ?>
                        </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div>
                    <label for="cover">cover:</label>
                    <input type="text" name="cover">
                </div>

                <div>
                    <label for="genre">Genres:</label>
                    <input type="text" name="genre">
                </div>

                <div>
                    <input type="submit" name="insert_album">
                </div>


            </form>
        </div>
    </main>

@endsection