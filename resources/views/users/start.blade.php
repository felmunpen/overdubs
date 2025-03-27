@extends('layouts.common')

@section('titulo', 'felmunpen')

@section('contenido')

    <main id="recent_albums_and_reviews">
        <div class="column" style="padding-top: 0%;">
            <div class="column_head">Last albums in our database:</div>

            <div id="recent_albums">
                <?php
    foreach ($albums as $album) {
        $string_total = '';
        echo "<div class=\"recent_album card\" style=\"display: grid; grid-template-columns: 2fr 4fr; column-gap: 4%;\">";
        // echo "<div style=\"width: 150px;\"><img src=\" " . $album->cover . " \"></div>";
        echo "<img src=\" " . $album->cover . " \">";

        echo "<div>";
        echo "<span style=\"font-size: x-large;\"><a href=\"show_album/" . $album->id . "\">" . $album->name . "</a></span><br>";
        echo "<span style=\"font-size: large;\"><a href=\"show_artist/" . $album->artist_id . "\">" . $album->artist_name . "</a></span><br>";
        if ($album->release_year !== 0) {
            echo "<span style=\"font-size: medium;\">" . $album->release_year . "</span><br>";
        }
        if ($album->average_rating > 0) {
            echo "<span style=\"font-size: medium;\">Average rating: $album->average_rating</span><br><br>";
        }
        echo "<span>Genres:<br> " . $album->genres_names . "</span>";
        echo "</div>";
        echo "</div>";
    }?>
            </div>
        </div>

        <div class="column" style="padding-top: 0%;">
            <div class="column_head">Last reviews from users:</div>

            <div id="recent_reviews">
                <?php
    foreach ($reviews as $review) {
        echo "<div class=\"recent_review card\">";
        echo "<div style=\"float:right; padding-right: 2.5%;\"><img class=\"square_img\" src=\" " . $review->cover . " \" width=\"75px\" height=\"75px\"></div>";
        echo "<div><span style=\"font-size:large;\"><a href=\"/show_album/" . $review->album_id . "\">" . $review->album_name . "</a></span><span> by <a href=\"show_artist/" . $review->artist_id . "\">" . $review->artist_name . "</a></span><br><span style=\"font-size:x-large;\">" . $review->rating . "</span><br><span style=\"font-style: italic;\">by <a href=\"/show_user/" . $review->user_id . "\">" . $review->user_name . "</a></span>";
        echo "<p>" . substr($review->text, 0, 80) . "... <br><a href=\"/show_album/" . $review->album_id . "/#" . $review->id . "\" style=\"float: right;\">See complete review.</a></p></div>";
        echo "</div>";
    }?>
            </div>

        </div>
    </main>

@endsection