@extends('layouts.common')

@section('title', 'Overdubs')

@section('content')

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

        <div>
            <h2>Reviews:</h2>
            <div>
                <?php foreach ($reviews as $review) {
        echo "<div class=\"review_header_2\">";
        echo "<span>$review->album_name</span>";
        echo "<span>$review->rating&nbsp&nbsp·&nbsp&nbsp<a href=\"/show_review/$review->id\">$review->title</a>&nbsp&nbspby&nbsp&nbsp<a href=\"/show_user/" . $review->user_id . "\">" . $review->user_name . "</a></span>";
        echo "<span><a href=\"/show_review/$review->id\">See review</a></span>";
        echo "</div>";
    }?>
            </div>
        </div>

        <div>
            <h2>Lists that contains albums by
                <?php echo $user->artist_name?>:
            </h2>

            <div>
                <?php foreach ($lists as $list) {
        echo "<div class=\"list_header_2\">";
        echo "<span><a href=\"/show_list/" . $list->id . "\">" . $list->name . "</a>&nbsp&nbspby&nbsp&nbsp<a href=\"/show_user/" . $list->user_id . "\">" . $list->user_name . "</a></span>";
        echo "<span><a href=\"/show_list/" . $list->id . "\">Show list</a></span>";
        echo "</div>";
    }?>
            </div>
        </div>
    </main>

    <!-- <script>
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
                                            }
                                        };

                                    </script> -->
@endsection