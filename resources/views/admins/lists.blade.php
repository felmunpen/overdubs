@extends('layouts.common')

@section('title', 'Overdubs')

@section('content')

    <main>
        <div style="margin:auto;"><input type="text" id="search" placeholder="Search..."></div>
        <h2>Lists:</h2>
        <table class="admin_table">
            <tr>
                <th></th>
                <th>Id</th>
                <th>Name</th>
                <th>User</th>
                <th>Show</th>
                <th>Delete</th>
            </tr>

            <?php
    foreach ($lists as $list) {
        echo "<tr>";
        echo "<td><img src=\"" . $list->list_pic . "\"></td>";
        echo "<td>" . $list->id . "</td>";
        echo "<td><a href=\"/show_list/" . $list->id . "\">" . $list->name . "</a></td>";
        echo "<td><a href=\"/show_user/" . $list->user_id . "\">" . $list->user_name . "</td>";
        echo "<td><a href=\"/show_list/" . $list->id . "\">Show</a></td>";
        echo '<td><form action="/delete_list/' . $list->id . '" method="POST" style="display:inline;">' . csrf_field() . '' . method_field('POST') . '<button type="submit" class="submit_camo">Delete</button></form></td>';
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