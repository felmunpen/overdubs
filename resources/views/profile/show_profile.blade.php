@extends('layouts.common')

@section('title', 'Overdubs')

@section('content')

    <main class="profile">
        <div class="profile_data column">
            <div><img src="<?php echo $user->profile_pic?>" alt="profile_pic" class="profile_pic"></div>

            <div>
                <br>
            </div>

            <div>
                <span>Nickname:</span><span>
                    <?php echo $user->name?>
                </span>
            </div>

            <div>
                <span>Email:</span><span>
                    <?php echo $user->email?>
                </span>
            </div>
            <div>
                <span>Usertype:</span><span>
                    <?php echo $user->usertype?>
                </span>
            </div>
            <div>
                <span>Birth year:</span><span>
                    <?php echo $user->year?>
                </span>
            </div>
            <div>
                <span>Gender:</span><span>
                    <?php echo $user->gender?>
                </span>
            </div>
            <div>
                <span>Country:</span><span>
                    <?php echo $user->country?>
                </span>
            </div>
            <div>
                <span style="color: red; font-weight: bold;">
                    <?php if ($user->blocked)
        echo "You are currently blocked."?>
                </span>
            </div>
            <div style="text-align: center;">
                <form method="GET" action="{{ route('profile.edit') }}">
                    <input type="submit" value="Edit profile" />
                </form>
            </div>
        </div>

        <div class="your_stuff column">
            @if(Auth::user()->usertype === 'User')
                <div class="tab">
                    <button class="tablinks" onclick="openTab(event, 'Reviews')" id="defaultOpen">Reviews</button>
                    <button class="tablinks" onclick="openTab(event, 'Lists')">Lists</button>
                    <button class="tablinks" onclick="openTab(event, 'Following')">Following</button>
                    <button class="tablinks" onclick="openTab(event, 'Followers')">Followers</button>
                    <button class="tablinks" onclick="openTab(event, 'Received DMs')">Received DMs</button>
                    <button class="tablinks" onclick="openTab(event, 'Sent DMs')">Sent DMs</button>
                    <button class="tablinks" onclick="openTab(event, 'Bio')">Bio</button>

                </div>
            @endif

            @if(Auth::user()->usertype === 'Artist')
                <div class="tab">
                    <button class="tablinks" onclick="openTab(event, 'Description')" id="defaultOpen">Description</button>
                    <button class="tablinks" onclick="openTab(event, 'Info')">Info</button>
                    <!-- <button class="tablinks" onclick="openTab(event, 'Followers')">Followers</button> -->
                </div>
            @endif

            @if(Auth::user()->usertype === 'Admin')
                <div class="tab">
                    <button class="tablinks" onclick="openTab(event, 'Lists')" id="defaultOpen">Lists</button>
                    <button class="tablinks" onclick="openTab(event, 'Following')">Following</button>
                    <button class="tablinks" onclick="openTab(event, 'Followers')">Followers</button>
                    <button class="tablinks" onclick="openTab(event, 'Received DMs')">Received DMs</button>
                    <button class="tablinks" onclick="openTab(event, 'Sent DMs')">Sent DMs</button>
                    <button class="tablinks" onclick="openTab(event, 'Bio')">Bio</button>

                </div>
            @endif


            @if(Auth::user()->usertype === 'User' || Auth::user()->usertype === 'Admin')
                <div id="Bio" class="tabcontent static_card">
                    <h2>Bio:</h2>
                    <?php    echo $user->bio?><br><br>
                    <form method="POST" action="{{ route('update_bio') }}">
                        @csrf
                        <textarea class="input_text" name="bio" required style="width: 100%" rows="5"
                            placeholder="Write your new bio here." maxlength="500"></textarea><br><br>
                        <input type="submit" name="Update_bio" value="Update bio" />
                    </form>
                </div>
            @endif

            @if(Auth::user()->usertype === 'User')

                    <div id="Reviews" class="tabcontent static_card">
                        <h2>Your reviews:</h2>
                        <table id="reviews_table">
                            <tr>
                                <th style="width: 12%;"></th>
                                <th style="width: 70%;"></th>
                                <th style="width: 9%;"></th>
                                <th style="width: 9%;"></th>
                            </tr>
                            <?php
                foreach ($reviews as $review) {
                    echo "<tr>";

                    echo "<td><img src=\" " . $review->cover . " \" class=\"list_img\"></td>";
                    echo "<td>" . $review->rating . " · <a href=\"/show_album/$review->album_id\">" . $review->album_name . "</a>";
                    if (isset($review->title) && $review->title !== '') {
                        echo " · " . $review->title;
                    }
                    echo "</td>";
                    echo "<td><a href=\"/show_review/$review->id\" class=\"link_button\">Show</a></td>";
                    echo '<td><form action="/delete_review/' . $review->id . '" method="POST" style="display:inline;">' . csrf_field() . '' . method_field('POST') . '<button type="submit" class="link_button">Delete</button></form></td>';
                    echo "</tr>";
                }?>
                        </table>
                    </div>
            @endif

            @if(Auth::user()->usertype === 'User' || Auth::user()->usertype === 'Admin')
                    <div id="Lists" class="tabcontent static_card">
                        <div>
                            <h2>Create a list:</h2>
                            <form method="POST" action="{{ route('created_list') }}" style="text-align: center;">
                                @csrf

                                <input type=" text" name="list_name" class="input_text_slim" placeholder="Name your new list."
                                    maxlength="100" />
                                <input type="text" name="list_pic" class="input_text_slim" placeholder="List cover URL."
                                    style="width: 50%;" />
                                <input type="submit" value="Create list" class="button_1" />
                            </form>
                        </div>
                        <div>
                            <h2>Your lists:</h2>
                            <table>
                                <tr>
                                    <th style="width: 10%;"></th>
                                    <th style="width: 70%;"></th>
                                    <th style="width: 7%;"></th>
                                    <th style="width: 13%;"></th>
                                </tr>

                                <?php
                foreach ($lists as $list) {
                    echo "<tr>";
                    echo "<td><img src=\"$list->list_pic\" class=\"list_img\"></td>";
                    echo "<td><a href=\"/show_list/$list->id\">$list->name</a></td>";
                    echo "<td><a href=\"/edit_list/$list->id\" class=\"link_button\">Edit</a></td>";
                    echo '<td><form action="/delete_list/' . $list->id . '" method="POST" style="display:inline;">' . csrf_field() . '' . method_field('POST') . '<button type="submit" class="link_button">Delete</button></form></td>';
                    echo "</tr>";
                }?>
                            </table>
                        </div>
                    </div>
            @endif

            @if(Auth::user()->usertype === 'User' || Auth::user()->usertype === 'Admin')
                    <div id="Following" class="tabcontent static_card">
                        <h2>Following to:</h2>
                        <table>
                            <tr>
                                <th style="width: 8%;"></th>
                                <th style="width: 82%;"></th>
                                <th style="width: 10%;"></th>
                            </tr>
                            <?php
                foreach ($followings as $following) {
                    echo "<tr>";
                    echo "<td><img src=\"" . $following->profile_pic . "\" class=\"list_img\"></td>";
                    echo "<td><a href=\"/show_user/" . $following->following_id . "\">" . $following->user_name . "</a></td>";
                    echo "<td><a href=\"#\">Unfollow</a></td>";
                    echo "</tr>";

                }?>
                        </table>
                    </div>

                    <div id="Followers" class="tabcontent static_card">
                        <h2>Followers:</h2>
                        <table>
                            <tr>
                                <th style="width: 8%;"></th>
                                <th style="width: 92%;"></th>
                            </tr>
                            <?php
                foreach ($followers as $follower) {
                    echo "<tr>";
                    echo "<td><img src=\"" . $follower->profile_pic . "\" style=\"vertical-align:middle\" width=\"50px\"></td>";
                    echo "<td><a href=\"/show_user/" . $follower->follower_id . "\">" . $follower->user_name . "</a></td>";
                    echo "</tr>";
                }?>
                        </table>
                    </div>

                    <div id="Received DMs" class="tabcontent static_card">
                        <h2>Received messages:</h2>
                        <table>
                            <tr>
                                <th style="width: 8%;"></th>
                                <th style="width: 4%;"></th>
                                <th style="width: 20%;"></th>
                                <th style="width: 60%;"></th>
                                <th style="width: 8%;"></th>
                            </tr>
                            <?php
                foreach ($messages_from as $message_from) {
                    echo "<tr>";
                    echo "<td>From:</td>";
                    echo "<td><img src=\"$message_from->profile_pic\" alt=\"user_pic\"></td>";
                    echo "<td>$message_from->user_name</td>";
                    echo "<td>$message_from->text<td>";
                    echo '<td><form action="/delete_message/' . $message_from->id . '" method="POST" style="display:inline;">' . csrf_field() . '' . method_field('POST') . '<button type="submit" class="link_button">Delete</button></form></td>';
                    echo "</tr>";
                }?>
                        </table>
                    </div>

                    <div id="Sent DMs" class="tabcontent static_card">
                        <h2>Sent messages:</h2>
                        <table>
                            <tr>
                                <th style="width: 8%;"></th>
                                <th style="width: 4%;"></th>
                                <th style="width: 20%;"></th>
                                <th style="width: 60%;"></th>
                                <th style="width: 8%;"></th>
                            </tr>
                            <?php
                foreach ($messages_to as $message_to) {
                    echo "<tr>";
                    echo "<td>To:</td>";
                    echo "<td><img src=\"$message_to->profile_pic\" alt=\"user_pic\"></td>";
                    echo "<td>$message_to->user_name</td>";
                    echo "<td>$message_to->text<td>";
                    // echo "<td><a href=\"/delete_message/$message_to->id\">Delete</a></td>";
                    echo '<td><form action="/delete_message/' . $message_to->id . '" method="POST" style="display:inline;">' . csrf_field() . '' . method_field('POST') . '<button type="submit" class="link_button">Delete</button></form></td>';
                    echo "</tr>";
                }?>
                        </table><br><br>

                    </div>
            @endif



            @if(Auth::user()->usertype === 'Artist')
                <div id="Description" class="tabcontent static_card">
                    <?php    echo $artist->description?><br><br>
                    <form method="POST" action="{{ route('update_description') }}">
                        @csrf
                        <textarea class="input_text" name="description" required style="width: 100%" rows="5"
                            placeholder="Write your new description here." maxlength="1000"></textarea><br><br>
                        <input type="submit" name="Update_description" value="Update description" />
                    </form>
                </div>

                <div id="Info" class="tabcontent static_card">
                    <?php    echo $artist->info?><br><br>
                    <form method="POST" action="{{ route('update_info') }}">
                        @csrf
                        <textarea class="input_text" name="info" required style="width: 100%" rows="5"
                            placeholder="Write your new info here." maxlength="1000"></textarea><br><br>
                        <input type="submit" name="Update_info" value="Update info" />
                    </form>
                </div>
            @endif
            <!-- </div> -->
        </div>
    </main>

    <script>
        document.getElementById("defaultOpen").click();

        function openTab(evt, tabName) {
            // Declare all variables
            var i, tabcontent, tablinks;

            // Get all elements with class="tabcontent" and hide them
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            // Get all elements with class="tablinks" and remove the class "active"
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }

            // Show the current tab, and add an "active" class to the button that opened the tab
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        } 
    </script>

@endsection