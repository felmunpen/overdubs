@extends('layouts.common')

@section('title', 'Overdubs')

@section('content')

    <main>
        <div class="show_album_card">
            <div id="cover_column" class="column">
                <div><img src="<?php echo $album->cover ?>" class="album_cover" style="border: thick solid white"></div>

                <div style="margin-top: 1%; margin-bottom: 6%; padding: 3% 4% 6% 4%; text-align: center;">



                    <div class="" style="padding: 3%;">
                        @if(Auth()->user()->usertype === 'User' && !$reviewed)
                            <a style="font-weight: normal;" class="link_button" href="#your_review" id="click_your_review">Write
                                your
                                review.</a>
                            &nbsp;
                        @endif

                        @if(Auth()->user()->usertype === 'User' && $reviewed)
                            <a style="font-weight: normal;" class="link_button"
                                href="/edit_review/<?php    echo $reviewed->id?>">Edit
                                your
                                review.</a>
                            &nbsp;
                        @endif

                        <!--(Que el artista no esté registrado / Que seas el artista / Que seas admin) Y  Que no estés bloqueado -->
                        @if(($album->artist_registered === 0 || Auth()->user()->id === $album->artist_user_id || Auth()->user()->usertype === "Admin") && Auth()->user()->blocked === 0)
                            <a style="font-weight: normal;" class="link_button"
                                href="/edit_album/<?php    echo $album->id?>">Edit
                                album
                                data.</a>
                        @endif
                    </div>

                    @if(count($user_lists) > 0)
                                    <form method="POST" action="{{ route('add_to_list') }}" style="padding-top: 1vh;">
                                        @csrf
                                        <input type="hidden" name="album_id" value="<?php    echo $album->id?>">
                                        <label for="lists"> Add this album to your lists: </label><br>
                                        <select name="list_id" required class="input_text">
                                            <?php    $count = count($user_lists);
                        foreach ($user_lists as $list) { ?>
                                            <option value=" <?php        echo $list->id; ?>">
                                                <?php        echo $list->name; ?>
                                            </option>
                                            <?php    } ?>
                                        </select>
                                        <input type="submit" name="send" value="Add" class="link_button">
                                    </form>
                    @endif
                </div>
            </div>
            <div id="data_column" class="column" style="">
                <div id="big_words" style="margin-bottom: 2%;">
                    <div class="album_title" style="font-size: xx-large; font-weight: bolder;">
                        <?php echo $album->name ?>
                    </div>
                    <div class="artist" style="font-size: x-large; font-weight: bolder;">
                        <a href="/show_artist/<?php echo $album->artist_id?>">
                            <?php echo $album->artist_name ?>
                        </a>
                        @if($album->artist_registered)

                            <span style="font-style: italic; font-weight: 400; font-size: 10pt;">Verified</span>
                        @endif

                    </div>
                    <div class="year" style="font-size: large; font-weight: normal;">
                        <?php echo $album->release_year ?>
                    </div>
                    @if($album->average_rating > 0)
                        <div style="font-size: large; font-weight: normal;">
                            <?php    echo "Average rating: " . $album->average_rating ?>
                        </div>
                    @endif
                    <div class="genres" style="margin-top: 10px;">
                        <form method="GET" action="{{ route('search_by_tag') }}">
                            @csrf
                            <input type="hidden" name="iterator" id="iterator" value="1">
                            <?php $genres_count = count($genres);
    for ($i = 0; $i < $genres_count; $i++) {
        echo "<input style=\"margin: 0.5vh;\" type=\"submit\" name=\"tag_name\" value=\"" . $genres[$i]->name . "\"/>";
        echo "&nbsp";
    }?>
                        </form>
                    </div>
                </div>
                <div>
                    Tracklist:<br>
                    <table class="tracklist" style="width: 80%; padding: 4vh;">
                        <th style="width: 10%;"></th>
                        <th style="width: 60%;"></th>
                        <th style="width: 30%;"></th>

                        <?php foreach ($songs as $song) {
        echo "<tr><td>" . $song->number . ".</td><td>" . $song->name . "</td><td style=\"text-align: right;\">" . $song->length . "</td><tr>";
    }?>
                    </table>
                    <br>
                </div>


            </div>
        </div>

        <div class="reviews">

            @if (Auth()->user()->usertype === "User")
                <div class="review_card" id="your_review">
                    <form method="POST" action="{{ route('send_review') }}">
                        @csrf
                        <label for="rating">Title: </label>
                        <input type="text" name="title" value="" placeholder="Give your review a title." class="input_text_slim"
                            style="width: 30%;" maxlength="50">
                        &nbsp;&nbsp;&nbsp;
                        <label for="rating">Rating: </label>
                        <select name="rating" class="input_text_slim" style="width: 10%;" required>
                            <?php    for ($i = 100; $i >= 0; $i--): ?>
                            <option value="<?php        echo $i; ?>">
                                <?php        echo $i; ?>
                            </option>
                            <?php    endfor; ?>
                        </select>
                        &nbsp;&nbsp;&nbsp;
                        <input type="submit" name="send" value="Send review">
                        <br>

                        <input type="hidden" name="album_id" value="<?php    echo $album->id?>">
                        <div style="padding-top: 1.5vh; padding-bottom: 4vh;">
                            <textarea class="input_text" name="review" style="width: 100%;" rows="10"
                                placeholder="Write your review here."></textarea>
                        </div>
                    </form>

                </div>
            @endif
            <br>
            <div>
                <?php if (count($reviews) > 0) {
        echo "<h2 style=\"padding-left: 4vh; margin-bottom:0vh;\">Reviews:</h2>";
    } else {
        echo "There's no reviews for this album yet. ";
        if (Auth()->user()->usertype === 'User') {
            echo "Be the first!";
        }
    }?>

                <?php foreach ($reviews as $review) {
        echo "<div class=\"review_card\" id=\"" . $review->id . "\">";
        echo "<div class=\"review_header\">";
        echo "<span><span class=\"rating\" style=\"font-weight: 700;\">" . $review->rating . "&nbsp·&nbsp</span>";
        echo "<span style=\"font-weight: 700;\">" . $review->title . "</span>";
        echo "<span style=\"font-weight: lighter;\" class=\"user\"> by <a href=\"/show_user/" . $review->user_id . "\">" . $review->user_name . "</a></span></span>";
        echo "<span>&nbsp<a href=\"/show_review/$review->id\">Show review</a></span>";
        echo "</div>";
        echo "<p>" . $review->text . "</p>";
        echo "</div>";
    }?>
            </div>
            <br><br>
            <div>
                <h2 style="padding-left: 4vh; margin-bottom:0vh;">If you like this album, you also may like:</h2>
                <div id="recommended_albums">
                    <?php
    foreach ($recommended_albums as $recommended_album) {
        echo "<div class=\"album_card card recommended\">";
        echo "<img src=\" " . $recommended_album->cover . " \">";
        echo "<div style=\"padding: 5%;\">";
        echo "<span style=\"font-size: large; font-weight: bold;\"><a href=\"/show_album/" . $recommended_album->id . "\">" . $recommended_album->name . "</a></span><br>";
        echo "</div>";
        echo "</div>";
    }?>
                </div>
            </div>
        </div>

    </main>

    <script>
        let review_alert = "<?php echo $review_alert ?>";

        window.onload = (event) => {
            if (review_alert != "") {
                alert(review_alert);
            }
        };

        document.getElementById("click_your_review").onclick = function () {
            // document.getElementById('your_review').style.height = 'auto';
            // document.getElementById('your_review').style.opacity = '1';
            var obj = document.getElementById('your_review'); //
            obj.style.padding = (obj.style.padding == '3% 5% 3% 5%') ? '0' : '3% 5% 3% 5%';
            obj.style.margin = (obj.style.margin == 'inherit') ? '0' : 'inherit';
            obj.style.visibility = (obj.style.visibility == 'visible') ? 'hidden' : 'visible';
            obj.style.height = (obj.style.height == '0px' || obj.style.height == '') ? '250px' : '0px';
        };

    </script>
@endsection