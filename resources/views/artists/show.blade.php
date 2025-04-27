@extends('layouts.common')

@section('title', 'Overdubs')

@section('content')

    <main>
        <div style="display: flex; gap: 20px">
            <div>
                <img src="<?php echo $artist->artist_pic?>" alt="artist_pic"
                    style="border: solid thick white; width:300px; height:300px; object-fit: cover;">
            </div>
            <div>
                <h1>
                    <?php echo $artist->name?>
                </h1>
                <p>
                    <?php echo $artist->description?>
                </p>
            </div>
        </div>


        <div>
            <h2 style="padding: 1% 5%;">Albums:</h2>
            <div class="searched_albums">
                <?php foreach ($albums as $album) {

        echo "<div class=\"album_card card\">";
        echo "<img src=\" " . $album->cover . " \" width=\"200px\" style=\"margin: auto; display:block\">";
        echo "<div style=\"padding: 5%;\">";
        echo "<span style=\"font-size: large; font-weight: bold;\"><a href=\"/show_album/" . $album->id . "\" class=\"\">" . $album->name . "</a></span><br>";
        echo "<span style=\"font-size: larger\">" . $album->release_year . "</span><br><br>";
        echo "</div>";
        echo "</div>";
    }?>
            </div>
        </div>

    </main>

@endsection