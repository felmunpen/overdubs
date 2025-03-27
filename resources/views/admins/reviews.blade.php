@extends('layouts.common')

@section('titulo', 'Inicio')

@section('contenido')

    <main>
        <h2>Reviews:</h2>
        <table class="admin_table">
            <tr>
                <th>Id</th>
                <th>User</th>
                <th>Album</th>
                <th>Artist</th>
                <th>Rating</th>
                <th>Text</th>
                <th></th>

            </tr>

            <?php
    foreach ($reviews as $review) {
        echo "<tr>";
        echo "<td>" . $review->id . "</td>";
        echo "<td>" . $review->user_name . "</td>";
        echo "<td>" . $review->album_name . "</td>";
        echo "<td>" . $review->artist_name . "</td>";
        echo "<td>" . $review->rating . "</td>";
        echo "<td>" . substr($review->text, 0, 20) . "</td>";
        echo "<td><a href=\"/show_album/" . $review->album_id . "/#" . $review->id . "\">Show</a></td>";
        echo "<td><a href=\"#\">Edit</a></td>";
        echo "</tr>";
    }?>

        </table>

    </main>

@endsection