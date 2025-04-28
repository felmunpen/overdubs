@extends('layouts.common')

@section('title', 'Overdubs')

@section('content')

    <main>
        <div style="margin:auto;"><input type="text" id="search" placeholder="Search..."></div>

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