@extends('layouts.common')

@section('title', 'Overdubs')

@section('content')

    <main>

        <div class="static_card"
            style="border: thin solid var(--intense); display: grid; grid-template-columns: 2fr 3fr; padding: 4vh 6vh; column-gap: 1vh;">

            <div><img src="<?php echo $review->album_cover ?>" class="album_cover" style="border: thick solid white"></div>

            <div>
                <h3>
                    <a href="/show_album/<?php echo $review->album_id?>">
                        <?php echo $review->album_name?>
                    </a>
                    by
                    <a href="/show_artist/<?php echo $review->artist_id?>">
                        <?php echo $review->artist_name?>
                    </a>
                </h3>
                <?php
    echo "<div class=\"review_header\">";
    echo "<span class=\"rating\" style=\"font-weight: 700;\">$review->rating&nbsp&nbsp·&nbsp&nbsp</span>";
    echo "<span style=\"font-weight: 700;\">$review->title</span>";
    echo "<span style=\"font-weight: lighter;\" class=\"user\">&nbsp&nbspby&nbsp&nbsp<a href=\"/show_user/$review->user_id\">$review->user_name</a></span>";
    echo "</div>";
    echo "<p>$review->text</p>"?>
            </div>
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

    </main>

@endsection