@extends('layouts.common')

@section('title', 'Overdubs')

@section('content')

    <main>
        <div>
            <h2>Álbums</h2>

            <?php

    $albums = DB::table('albums')->get();

    foreach ($albums as $album) {
        echo "Título: " . $album->name . " - <a href=\"show_album/" . $album->id . "\">Show album</a>" . ".<br><br>";
    }

                        ?>
        </div>
    </main>

@endsection