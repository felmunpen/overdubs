@extends('layouts.common')

@section('title', 'Overdubs')

@section('content')

    <main>
        <h2>Albums:</h2>
        <table class="admin_table">
            <tr>
                <th></th>
                <th>Id</th>
                <th>Name</th>
                <th>Artist</th>
                <th>Release Year</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>

            <?php


    foreach ($albums as $album) {
        echo "<tr>";
        echo "<td><img src=\"" . $album->cover . "\"></td>";

        echo "<td>" . $album->id . "</td>";
        echo "<td><a href=\"/show_album/" . $album->id . "\">" . $album->name . "</a></td>";
        echo "<td><a href=\"/show_artist/" . $album->artist_id . "\">" . $album->artist_name . "</td>";
        echo "<td>" . $album->release_year . "</td>";
        echo "<td><a href=\"/edit_album/" . $album->id . "\">Edit</a></td>";
        echo "<td><a href=\"/delete_album/" . $album->id . "\">Delete</a></td>";
        echo "</tr>";
    }?>

        </table>

    </main>

@endsection