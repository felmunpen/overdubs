@extends('layouts.common')

@section('titulo', 'Inicio')

@section('contenido')

    <main>
        <h2>Users:</h2>
        <table class="admin_table">
            <tr>
                <th></th>
                <th>Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Usertype</th>
                <th>Gender</th>
                <th>Birth year</th>
                <th>Blocked</th>
                <th></th>
                <th></th>
            </tr>

            <?php
    foreach ($users as $user) {
        if ($user->blocked) {
            $blocked = 'Yes';
            $blocked_button = "Unblock";
        } else {
            $blocked = 'No';
            $blocked_button = "Block";
        }

        echo "<tr>";
        echo "<td><img  src=\"" . $user->profile_pic . "\"</td>";
        // echo "<td></td>";

        echo "<td>" . $user->id . "</td>";
        echo "<td><a href=\"/show_user/" . $user->id . "\">" . $user->name . "</a></td>";
        echo "<td>" . $user->email . "</td>";
        echo "<td>" . $user->usertype . "</td>";
        echo "<td>" . $user->gender . "</td>";
        echo "<td>" . $user->year . "</td>";
        echo "<td>" . $blocked . "</td>";
        echo "<td><a class=\"button_1\" href=\"users/block/" . $user->id . "\">" . $blocked_button . "</a></td>";
        echo "<td><a id=\"" . $user->name . "-" . $user->id . "\" class=\"button_1 message\" href=\"#message_form\">Message</a></td>";
        echo "</tr>";
    }?>

        </table>

        <div id="message_form" style="margin-top: 5vh;">
            <p id="message_receiver"></p>
            <form method="POST" action="{{ route('sent_message') }}">
                @csrf

                <div>
                    <label for="user"></label>
                    <input type="hidden" name="user" id="to" required readonly>
                </div>

                <div>
                    <label for="id"></label>
                    <input type="hidden" name="id" id="id" required readonly>
                </div>

                <div>
                    <label for="message"></label>
                    <textarea name="message" style="width: 100%; padding: 2vh;" rows=7
                        placeholder="Write the message here..." required></textarea>
                </div>

                <div>
                    <input style="float: right;" type="submit" name="send" value="Send message" style="">
                </div>

            </form>
        </div>

    </main>

    <script>
        $(".message").click(function () {
            // alert("pulsaste viejo ->" + this.id);
            particion = this.id.lastIndexOf("-");
            document.getElementById("to").value = this.id.substring(0, particion);
            document.getElementById("id").value = this.id.substring(particion + 1);
            document.getElementById("message_receiver").innerHTML = "Message for " + this.id.substring(0, particion) + ":";

        });

    </script>

@endsection