@extends('layouts.common')

@section('titulo', 'Inicio')

@section('contenido')

<main>
    <div>
        <h2>SHOW</h2>
        <?php echo "Quieres ver el album con id " . $id . ".<br><br>"; ?>
        <?php $album = DB::table('albums')->where('id', $id)->first();
$songs = DB::table('songs')->where('album_id', $id)->get();
$artist = DB::table('artists')->where('id', $album->artist_id)->first();
$reviews = DB::table('reviews')->where('album_id', $id)->get();


echo "Título: " . $album->name . "<br><br> 
        Artist: " . $artist->name . " <br><br> 
        Album cover: <img src=\" " . $album->cover . " \" width=\"200\"><br><br>
        Release year: " . $album->release_year . " <br><br>
        Genres: " . $album->genre . " <br><br>
        Songs:<br><br>";

foreach ($songs as $song) {
    echo $song->order . ". " . $song->name . ".<br>";
}

echo "<br><br>";

foreach ($reviews as $review) {
    $user = DB::table('users')->where('id', $review->user_id)->first();

    echo "Rating: " . $review->rating . " by " . $user->name . ".<br><br>" . $review->text . ".<br><br>";
}

echo "<br><br>";

        
        ?>

    </div>
</main>

@endsection