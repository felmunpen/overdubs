@extends('layouts.common')

@section('titulo', 'Inicio')

@section('contenido')

    <main>

        <div class="static_card"
            style="border: thin solid var(--intense); display: grid; grid-template-columns: 2fr 3fr; padding: 3vh; column-gap: 2vh;">

            <div><img src="<?php echo $review->album_cover ?>" class="album_cover" style="border: thick solid white"></div>

            <div>
                <h3>
                    <?php echo $review->album_name . " by " . $review->artist_name?>
                </h3>
                <?php
    echo "<div class=\"review_header\">";
    echo "<span class=\"rating\" style=\"font-weight: 700;\">$review->rating&nbsp·&nbsp</span>";
    echo "<span style=\"font-weight: 700;\">$review->title</span>";
    echo "<span style=\"font-weight: lighter;\" class=\"user\"> by <a href=\"/show_user/$review->user_id\">$review->user_name</a></span>";
    echo "</div>";
    echo "<p>$review->text</p>"?>
            </div>
        </div>

    </main>

@endsection