@extends('layouts.common')

@section('titulo', 'Inicio')

@section('contenido')

    <main>
        <div class="show_user_card">
            <div style="display: flex; gap: 20px">
                <div>
                    <img src="<?php echo $user->profile_pic ?>" alt="user_pic"
                        style="border: solid thick white; width:200px; height:200px; object-fit: cover;">
                </div>
                <div>
                    <h1>
                        <?php echo $user->name?>
                    </h1>
                    <p>
                        <?php echo $user->bio?>
                    </p>

                    @if($user->usertype === 'User' || $user->usertype === 'Admin')
                                    <!-- <div style="display: grid; grid-template-columns: auto auto;"> -->
                                    <div>
                                        <div>
                                            <form method="POST" action="{{ route('follow_or_unfollow') }}">
                                                @csrf
                                                <?php    echo "<input type=\"hidden\" name=\"follow\" value=\"" . $follow . "\"/>";
                        echo "<input type=\"hidden\" name=\"following_id\" value=\"" . $user->id . "\"/>";
                        if (Auth::user()->id !== $user->id && $follow === true) {
                            echo "<input type=\"submit\" value=\"Unfollow\"></a>";
                        } elseif (Auth::user()->id !== $user->id && $follow === false) {
                            echo "<input type=\"submit\" value=\"Follow\"></a>";
                        }?>
                                            </form>
                                            <a class="link_button" id="send_message_link">Write a message</a>
                                        </div>
                                        <div id="message_form">
                                            <form method="POST" action="{{ route('send_message') }}">
                                                @csrf
                                                <input type="hidden" name="receiver_id" value="<?php    echo $user->id?>">
                                                <textarea name="message" placeholder="Write your message." maxlength="300" rows="7"
                                                    style="padding: 1vh; width: 100%;"></textarea>
                                                <input style="float:right; margin-top: 1vh;" type="submit" value="Send">
                                            </form>
                                        </div>
                                    </div>
                    @endif
                </div>
            </div>

            <div>
                @if($user->usertype === 'User' || $user->usertype === 'Admin')
                            <div>
                                <h2>Reviews:</h2>
                                <div id="user_reviews">
                                    <?php    foreach ($reviews as $review) {
                        echo "<div class=\"review_card\">";
                        echo "<div class=\"review_header\">";
                        echo "<span class=\"rating\"><a style=\"color: white;\" href=\"/show_album/$review->album_id\">$review->album_name</a>&nbsp·&nbsp</span>";

                        echo "<span class=\"rating\">$review->rating&nbsp·&nbsp</span>";
                        echo "<span>$review->title</span>";
                        echo "</div>";
                        echo "<p>$review->text</p>";
                        echo "</div>";
                    }?>
                                </div>
                            </div>

                            <div>
                                <h2>Lists:</h2>
                                <div id="user_lists">
                                    <?php    $i = 0;
                    foreach ($lists as $list) {

                        $albums_in_list = DB::table('lists_elements')->where('list_id', $list->id)
                            ->join('albums', 'lists_elements.album_id', '=', 'albums.id')
                            ->join('artists', 'albums.artist_id', '=', 'artists.id')
                            ->select('lists_elements.*', 'albums.name as album_name', 'artists.name as artist_name')
                            ->limit(2)
                            ->get();

                        if (count($albums_in_list) === 2) {
                            $string = "including " . $albums_in_list[0]->album_name . ", " . $albums_in_list[1]->album_name . " and more.";
                        } else {
                            $string = "";
                        }

                        echo "<div class=\"review_card\">";
                        echo "<span class=\"rating\" style=\"font-size: 12pt; font-weight: 700;\"><a href=\"/show_list/$list->id\">$list->name</a> </span>";
                        echo "<span  style=\"font-style: italic; font-weight: lighter;\">" . $string . "</span>";
                        echo "</div>";
                        ++$i;
                    }?>
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; column-gap: 5%;">
                                <div id=" Following">
                                    <h2>Following:</h2>
                                    <table id="user_followings">
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
                        echo "</tr>";

                    }?>
                                    </table>
                                </div>

                                <div id="Followers">
                                    <h2>Followers:</h2>

                                    <table id="user_followers">
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
                            </div>
                @endif
            </div>
        </div>

    </main>

    <script>

        let send_message_link = document.getElementById('send_message_link');
        let message_form = document.getElementById('message_form');

        send_message_link.onclick = function () {
            if (message_form.style.opacity = '0') {
                message_form.style.visibility = 'visible';
                message_form.style.opacity = '1';
            }
        }
    </script>
@endsection