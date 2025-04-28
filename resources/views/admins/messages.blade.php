@extends('layouts.common')

@section('title', 'Overdubs')

@section('content')

    <main>
        <div style="margin:auto;"><input type="text" id="search" placeholder="Search..."></div>
        <h2>Messages:</h2>
        <table class="admin_table">
            <tr>
                <th>Id</th>
                <th>Sender</th>
                <th>Receiver</th>
                <th>Text</th>
                <th>Delete</th>
            </tr>

            <?php


    foreach ($messages as $message) {
        echo "<tr>";
        echo "<td>" . $message->id . "</td>";
        echo "<td><a href=\"/show_user/" . $message->sender_id . "\">" . $message->sender_name . "</a></td>";
        echo "<td><a href=\"/show_user/" . $message->receiver_id . "\">" . $message->receiver_name . "</td>";
        echo "<td>" . $message->text . "</td>";
        echo '<td><form action="/delete_message/' . $message->id . '" method="POST" style="display:inline;">' . csrf_field() . '' . method_field('POST') . '<button type="submit" class="submit_camo">Delete</button></form></td>';
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