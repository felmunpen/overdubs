@extends('layouts.common')

@section('titulo', 'Inicio')

@section('contenido')

    <main>
        @if (Auth()->user()->usertype === "User" || Auth()->user()->usertype === "Admin")
            <div class="hey">
                If you can't find an album, maybe it's not in our database yet. Don't worry!
                You
                can include it with a
                couple of clicks.
                <a href="insert_album" id="click_here">Click here!</a>
            </div>
        @endif

        <div style="">
            <?php if ($search != "") {
        echo "<span>Results for \"" . $search . "\":</span>";
    } ?>

            <div style="display: inline-flex; margin-top: 1vh;">
                <form method="GET" action="{{ route('search') }}">
                    @csrf
                    <input type="hidden" name="search" id="search" value="<?php    echo $search?>" readonly />
                    <input type="hidden" name="iterator" id="iterator" value="<?php    echo $iterator - 1?>" readonly />
                    <input type="<?php    echo ($iterator === "1") ? "hidden" : "submit"?>" name="page" id="previous"
                        value="Previous" />
                </form>

                &nbsp;&nbsp;

                @if($pages === 1)
                    <form method="GET" action="{{ route('search') }}" style="text-align: right; ">
                        @csrf
                        <input type="hidden" name="search" id="search" value="<?php    echo $search?>" readonly />
                        <input type="hidden" name="iterator" id="iterator" value="<?php    echo $iterator + 1?>" readonly />
                        <input type="<?php    echo ($iterator === strval($pages)) ? "hidden" : "submit"?>" name="page" id="next"
                            value="Next" />
                    </form>
                @endif

            </div>
        </div>
        <div class="searched_albums">

            <?php
    foreach ($albums as $album) {
        echo "<div class=\"album_card card\">";
        echo "<img src=\" " . $album->cover . " \">";
        echo "<div style=\"padding: 5%;\">";
        echo "<span style=\"font-size: large; font-weight: bold;\"><a href=\"show_album/" . $album->id . "\">" . $album->name . "</a></span><br>";
        echo "<span style=\"font-size: larger\"><a href=\"show_artist/" . $album->artist_id . "\">" . $album->artist_name . "</a></span><br>";
        echo "<span style=\"font-size: larger\">" . $album->release_year . "</span><br><br>";
        echo "</div>";
        echo "</div>";
    }?>

        </div>


    </main>

@endsection