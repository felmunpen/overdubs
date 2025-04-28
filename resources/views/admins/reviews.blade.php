@extends('layouts.common')

@section('title', 'Overdubs')

@section('content')

    <main>
        <div style="margin:auto;"><input type="text" id="search" placeholder="Search..."></div>
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
        echo "<td><a href=\"/show_review/" . $review->id . "\">Show</a></td>";
        echo '<td><form action="/delete_review/' . $review->id . '" method="POST" style="display:inline;">' . csrf_field() . '' . method_field('POST') . '<button type="submit" class="submit_camo">Delete</button></form></td>';

        echo "</tr>";
    }?>

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
        </table>

    </main>

@endsection