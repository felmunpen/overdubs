@extends('layouts.common')

@section('titulo', 'Inicio')

@section('contenido')

<main>
    <div>
        <h2>Selected album:
            <?php echo $album_db_id . '<br>'?>
            <?php echo $title . '<br>'?>
            <?php echo $artist_name . '<br>'?>

        </h2>
    </div>
</main>

@endsection