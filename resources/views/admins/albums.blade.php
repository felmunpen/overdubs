@extends('layouts.common')

@section('title', 'Overdubs')

@section('content')

    <main>
        <div style="margin:auto;"><input type="text" id="search" placeholder="Search..."></div>

        <h2>Albums:</h2>
        <table class="admin_table">
            <tr>
                <th></th>
                <th>Id</th>
                <th>Name</th>
                <th>Artist</th>
                <th>Release Year</th>
                <th></th>
                <th></th>
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
        echo '<td><form action="/delete_album/' . $album->id . '" method="POST" style="display:inline;">' . csrf_field() . '' . method_field('POST') . '<button type="submit" class="submit_camo">Delete</button></form></td>';
        echo "</tr>";
    }?>

        </table>

        <script>
            document.getElementById('search').addEventListener('keyup', function () {
                let filter = this.value.toLowerCase();
                let rows = document.querySelectorAll('.admin_table tbody tr');

                rows.forEach(function (row) {
                    let cells = row.getElementsByTagName('td');
                    let match = false;

                    for (let i = 0; i < cells.length; i++) {
                        if (cells[i].textContent.toLowerCase().indexOf(filter) > -1) {
                            match = true;
                        }
                    }

                    if (match) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        </script>
    </main>

@endsection