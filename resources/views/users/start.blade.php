@extends('layouts.common')

@section('title', 'Overdubs')

@section('content')

    <main id="recent_albums_and_reviews">
        <div class="column" style="padding-top: 0%;">
            <div class="column_head static_card">Last albums in our database:</div>

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
            <!-- <div class="column_head static_card">Top albums:</div> -->
            <div class="recent_review card" style="display:grid; grid-template-columns: 2fr 1fr;">
                <div>
                    Top albums in our database:
                    <br><br>
                    <?php $i = 1;
    foreach ($top_10_albums as $top_10_album) {
        echo $i . ". <a href=\"/show_album/$top_10_album->id\" id=\"$top_10_album->id\" data-image=\"$top_10_album->cover\" class=\"trigger\">$top_10_album->name</a>";
        echo "<br>";
        ++$i;
    }?>
                </div>
                <div id="slideshow">
                    <br><br>
                    <img src="<?php echo $top_10_albums[0]->cover ?>" alt=":)" id="top_10_cover">
                </div>


            </div>
            <br>
            <div class="column_head static_card">Last reviews from users:</div>

            <div id="recent_reviews">
                <?php
    foreach ($reviews as $review) {
        echo "<div class=\"recent_review card\">";
        echo "<div style=\"float:right; padding-right: 2.5%;\"><img class=\"square_img\" src=\" " . $review->cover . " \" width=\"75px\" height=\"75px\"></div>";
        echo "<div><span style=\"font-size:large;\"><a href=\"/show_album/" . $review->album_id . "\">" . $review->album_name . "</a></span><span> by <a href=\"show_artist/" . $review->artist_id . "\">" . $review->artist_name . "</a></span><br><span style=\"font-size:x-large;\">" . $review->rating . "</span><br><span style=\"font-style: italic;\">by <a href=\"/show_user/" . $review->user_id . "\">" . $review->user_name . "</a></span>";
        echo "<p>" . substr($review->text, 0, 80) . "... <br><a href=\"/show_review/" . $review->id . "\" style=\"float: right;\">See complete review.</a></p></div>";
        echo "</div>";
    }?>
            </div>

        </div>
    </main>

    <script>
        // const top_10_cover = document.getElementById('top_10_cover');
        // const links = document.querySelectorAll('a[data-image]');
        // let currentImage = top_10_cover.src;

        // links.forEach(link => {
        //     link.addEventListener('mouseenter', () => {
        //         const newSrc = link.getAttribute('data-image');
        //         if (newSrc === currentImage) return;

        //         const tempImage = new Image();
        //         tempImage.src = newSrc;

        //         // Cuando la imagen ya está cargada
        //         tempImage.onload = () => {
        //             top_10_cover.style.opacity = 0;

        //             top_10_cover.addEventListener('transitionend', function handler() {
        //                 top_10_cover.removeEventListener('transitionend', handler);
        //                 top_10_cover.src = newSrc;
        //                 top_10_cover.style.opacity = 1;
        //                 currentImage = newSrc;
        //             });
        //         };
        //     });
        // });

        const image = document.getElementById('top_10_cover');
        const links = document.querySelectorAll('a[data-image]');
        let imageList = Array.from(links).map(link => link.getAttribute('data-image'));
        let currentIndex = 0;
        let intervalId;
        let isHovering = false;

        // Función que cambia la imagen con transición
        function changeImage(newSrc) {
            if (image.src.endsWith(newSrc)) return; // No cambies si ya es la misma
            image.style.opacity = 0;
            setTimeout(() => {
                image.src = newSrc;
                image.style.opacity = 1;
            }, 300);
        }

        // Slideshow automático
        function startSlideshow() {
            intervalId = setInterval(() => {
                if (!isHovering) {
                    currentIndex = (currentIndex + 1) % imageList.length;
                    changeImage(imageList[currentIndex]);
                }
            }, 5000); // cambia cada 3 segundos
        }

        // Manejo de eventos de hover
        links.forEach((link, index) => {
            link.addEventListener('mouseenter', () => {
                isHovering = true;
                changeImage(link.getAttribute('data-image'));
            });

            link.addEventListener('mouseleave', () => {
                isHovering = false;
                currentIndex = index; // Sigue desde la imagen actual
            });
        });

        // Inicia el slideshow
        startSlideshow();


    </script>

@endsection