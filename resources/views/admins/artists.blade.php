@extends('layouts.common')

@section('titulo', 'Inicio')

@section('contenido')

    <main>
        <h2>Artists:</h2>
        <table class="admin_table">
            <tr>
                <th></th>
                <th>Id</th>
                <th>Name</th>
                <th>Formation year</th>

                <th>Registered</th>
                <th>User ID</th>

                <!-- <th>Edit</th><th>Delete</th> -->
            </tr>

            <?php


    foreach ($artists as $artist) {
        echo "<tr>";
        echo "<td><img src=\"" . $artist->artist_pic . "\"></td>";
        echo "<td>" . $artist->id . "</td>";
        echo "<td><a href=\"/show_artist/" . $artist->id . "\">" . $artist->name . "</td>";

        echo "<td>" . $artist->year . "</td>";

        if ($artist->registered) {
            $artist->registered = "Yes";
        } else {
            $artist->registered = "No";
        }
        echo "<td>" . $artist->registered . "</td>";

        echo "<td><a href=\"/show_user/" . $artist->user_id . "\">" . $artist->user_id . "<a></td>";

        echo "</tr>";
    }?>

        </table>

    </main>

@endsection