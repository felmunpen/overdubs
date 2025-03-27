@extends('layouts.common')

@section('titulo', 'Inicio')

@section('contenido')

    <main>
        <div style="display: flex; gap: 20px">
            <div>
                <img src="<?php echo $user->profile_pic?>" alt="artist_pic"
                    style="border: solid thick white; max-width:400px; max-height:400px; object-fit: cover;">
            </div>
            <div>
                <h1>
                    <?php echo $user->artist_name?>
                </h1>
                <p>
                    <?php echo $user->artist_description?>
                </p>
                <p>
                    <?php echo $user->artist_info?>
                </p>
            </div>
        </div>

        <div>
            <h2 style="padding: 1% 5%;">Albums:</h2>
            <div class="searched_albums">
                <?php foreach ($albums as $album) {

        echo "<div class=\"album_card static_card\">";
        echo "<img src=\" " . $album->cover . " \" width=\"200px\" style=\"margin: auto; display:block\">";
        echo "<div style=\"padding: 5%;\">";
        echo "<span style=\"font-size: large; font-weight: bold;\"><a href=\"show_album/" . $album->id . "\" class=\"\">" . $album->name . "</a></span><br>";
        echo "<span style=\"font-size: larger\">" . $album->release_year . "</span><br><br>";
        echo "</div>";
        echo "</div>";
    }?>
            </div>
        </div>

        <!--SOLO SALEN LINKS EN LA PRIMERA ITERACIÓN, NO SÉ POR QUÉ, PASAR A TABLA?-->
        <h2 style="padding: 1% 5%;">Reviews:</h2>
        <div style="display:flex">
            <div style="width: 50%; margin-right: 2%;">
                <?php foreach ($reviews as $review) {
        echo "<div class=\"review_header_2\">";
        echo "<span>" . $review->album_name . "</span>";
        echo "<span><span id=\"review_rating_" . $review->id . "\">" . $review->rating . "</span> - <span id=\"review_title_" . $review->id . "\">Título de la review </span><span id=\"review_author_" . $review->id . "\">by <a href=\"/show_user/" . $review->user_id . "\">" . $review->user_name . "</a></span></span>";
        echo "<span><a href=\"#review_link_" . $review->id . "\" class=\"review_link\" id=\"review_link_" . $review->id . "\">See review</a></span>";
        echo "</div>";

        echo "<div class=\"hidden_review\" id=\"review_text_" . $review->id . "\">";
        echo "<span>" . $review->text . "</span>";
        echo "<span><a href=\"#\">Report review.</a></span>";
        echo "</div>";
    }?>
            </div>

            <div class="show_review" style="width: 38%; border: solid thin white; padding:2%;">

                @if($reviews->first())
                    <div id="review_title">Título de la review</div>
                    <div id="review_rating">
                        <?php    echo $reviews->first()->rating; ?>
                    </div>
                    <div id="review_author">by
                        <?php    echo $reviews->first()->user_name; ?>
                    </div>
                    <p id="review_text">
                        <?php    echo $reviews->first()->text; ?>
                    </p>
                @endif
            </div>


        </div>

        <h2 style="padding: 1% 5%;">Lists that contains albums by
            <?php echo $user->artist_name?>:
        </h2>
        <div style="display:flex">

            <div style="width: 50%; margin-right: 2%;">
                <?php foreach ($lists as $list) {
        echo "<div class=\"list_header_2\">";
        // echo "<span><span>" . $list->name . "</span><span> by " . $list->user_name . "</span></span>";
        // echo "<span><a href=\"#show_list/" . $list->id . "\">Show list</a></span>";
        echo "</div>";

    }?>
            </div>

            <div class="show_list" style="width: 38%; border: solid thin white; padding: 2%;">
                <div id="list_name">
                </div>
                <div id="list">

                </div>
            </div>

        </div>
    </main>

    <script>
        var review_links = document.getElementsByClassName("review_link");

        for (var i = 0; i < review_links.length; i++) {
            review_links[i].onclick = function () {
                console.log(this.id);
                var id_clean = this.id.substring(12);

                var review_title = document.getElementById('review_title_' + id_clean);
                var review_rating = document.getElementById('review_rating_' + id_clean);
                var review_author = document.getElementById('review_author_' + id_clean);
                var review_text = document.getElementById('review_text_' + id_clean);

                document.getElementById('review_title').innerHTML = review_title.innerHTML;
                document.getElementById('review_rating').innerHTML = review_rating.innerHTML;
                document.getElementById('review_author').innerHTML = review_author.innerHTML;
                document.getElementById('review_text').innerHTML = review_text.innerHTML;


                // review_text.style.height = (review_text.style.height == '0px' || review_text.style.height == '') ? '225px' : '0px';
                // review_text.style.padding = (review_text.style.padding == '3% 5% 3% 5%') ? '0' : '3% 5% 3% 5%';
                // review_text.style.margin = (review_text.style.margin == 'inherit') ? '0' : 'inherit';
                // review_text.style.visibility = (review_text.style.visibility == 'visible') ? 'hidden' : 'visible';

            }
        };

    </script>
@endsection