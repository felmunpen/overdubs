@extends('layouts.common')

@section('title', 'Overdubs')

@section('content')

    <main>
        <h2>Edit album information:</h2>
        <form class="show_album_card" method="POST" action="{{ route('edited_album') }}" style="padding-bottom: 5vh;">
            @csrf
            <div id="cover_column" class="column">
                <div style="margin-bottom: 3vh;"><img src="<?php echo $album->cover ?>" class="album_cover"
                        style="border: thick solid white">
                </div>

                <div>
                    <label for="cover">Cover URL:</label><br>
                    <input type="text" name="cover" placeholder="<?php echo $album->cover?>"
                        value="<?php echo $album->cover?>" maxlength="300" class="input_text_slim">
                </div>
                <div class="genres" style="margin-top: 1vh; margin-bottom: 2vh;">
                    <div style="margin-bottom: 1vh;">Genres:</div>
                    <div style="margin-bottom: 1vh;">Check the boxes of the genres or tags you want to delete:</div>
                    <div style="margin-bottom: 2vh; display: grid; grid-template-columns: 1fr 1fr; row-gap: 2vh;">
                        <?php foreach ($genres as $genre) {
        echo "<span><input type=\"checkbox\" name=\"delete_genres[]\" value=\"$genre->name\"><label class=\"tag\" for=\"$genre->name\" style=\"margin-right: 1vh;\">$genre->name</label></span>";
    }?>
                    </div>
                    <div style=" margin-bottom: 1vh;">
                        <span>If you want to add new genres, subgenres, or tags, write them below separated by
                            commas.</span>
                        <br>

                        <input type="text" class="input_text_slim" name="new_genres"
                            placeholder="e.g: rock, funk, psychedelic...">
                    </div>
                </div>

                <div>
                    <input type="submit" name="insert_album" value="Apply changes." style="margin: auto; width: 50%;">
                </div>
            </div>
            <div id="data_column" class="column" style="">
                <input type="hidden" name="album_id" value="<?php    echo $album->id?>">
                <div id="big_words" style="margin-bottom: 2%;">
                    <div class="album_title" style="margin-bottom: 2vh;">
                        <label for="album_name">Title:</label><br>
                        <input type="text" name="album_name" required placeholder="<?php echo $album->name?>"
                            value="<?php echo $album->name?>" class="input_text">
                        <br>
                        <span>by
                            <?php echo $album->artist_name ?>
                        </span>
                    </div>
                    <div class="year" style="">
                        <label for="release_year">Release year:</label><br>
                        <select name="release_year" id="release_year" class="input_text">
                            <option selected="selected" value="<?php echo $album->release_year?>">
                                <?php echo $album->release_year?>
                            </option>
                            <?php for ($i = 1900; $i <= 2025; $i++): ?>
                            <option value="<?php    echo $i; ?>">
                                <?php    echo $i; ?>
                            </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                </div>
                <br>
                <div class="clear" style="padding: 2% 3% 4% 3%;">
                    Tracklist:
                    <table class="tracklist">
                        <tr>
                            <th style="width: 15%"></th>
                            <th style="width: 70%"></th>
                            <th style="width: 15%"></th>
                        </tr>
                        <?php $number_of_songs = count($songs);
    for ($i = 0; $i < $number_of_songs; $i++) {
        echo "<tr><td>" . $songs[$i]->number . ".<input type=\"hidden\" name=\"songs[" . $i . "][]\" value=\"" . $songs[$i]->id . "\"></td><td><input type=\"text\" class=\"input_text_slim\" name=\"songs[" . $i . "][]\" value=\"" . $songs[$i]->name . "\"></td><td><input type=\"text\" class=\"input_text_slim\" name=\"songs[" . $i . "][]\" value=\"" .
            $songs[$i]->length . "\" pattern=\"[0-9]+:[0-9]{2}\"></td><tr>";
    }?>
                    </table>
                </div>

            </div>
        </form>

    </main>

@endsection