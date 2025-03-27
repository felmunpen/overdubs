@extends('layouts.common')

@section('titulo', 'Inicio')

@section('contenido')

<main>
    <div>
        <h2>SEARCH</h2>
        <?php echo "Has buscado: " . $search . ".<br><br>"; ?>
        <h2>RESULTADOS</h2>
        <?php
$results = DB::table('albums')->where('album_name', 'LIKE', '%' . $search . '%')->get();
foreach ($results as $result) {
    /*¿Esto es muy bestia o subóptimo?*/
    $artist = DB::table('artists')->where('artist_id', $result->artist_id)->first();
    echo "Título: " . $result->album_name . " - Artist: " . $artist->artist_name . " - <a href=\"show_album/" . $result->album_id . "\">Show album</a>" . ".<br><br>";
    echo "Album cover: <img src=\" " . $result->cover . " \" width=\"200\"><br><br>";

}
        ?>
    </div>
</main>

@endsection