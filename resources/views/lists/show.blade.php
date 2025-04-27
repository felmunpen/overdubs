@extends('layouts.common')

@section('title', 'Overdubs')

@section('content')

    <main>
        <div class="show_list_card">
            <div>
                <h3>
                    <?php echo $list->name ?>
                </h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; column-gap: 1vh;">
                    <img src="<?php echo $list->list_pic ?>" class="list_cover" style="border: thick solid white">
                    <div>
                        <span style="font-style: italic;">by<br><a style="font-size: larger;"
                                href="/show_user/<?php echo $list->user_id?>">
                                <?php echo $list->user_name?>
                            </a></span><br><br>
                        @if (Auth::user()->id === $list->user_id)
                            <a href="/edit_list/<?php    echo $list->id?>" class="link_button">Edit</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="static_card"
                style="padding: 2vh 4vh; border: thin solid var(--dark_grey); background-color: var(--clear_grey);">
                <table class="list_table">

                    <tr>
                        <th style="width:12%"></th>
                        <th style="width:22%">Cover</th>
                        <th style="width:22%">Album</th>
                        <th style="width:22%">Artist</th>
                        <th style="width:22%"></th>
                    </tr>
                    <?php $position = 1;
    foreach ($albums as $album) {
        echo "<tr><td>$position</td><td><img src=\"$album->album_cover\"></td><td><a href=\"/show_album/$album->album_id\">$album->album_name</a></td><td><a href=\"/show_artist/$album->artist_id\">$album->artist_name</a></td>";
        if (Auth::user()->id === $list->user_id) {
            echo "<td><a href=\"/remove_from_list/$list->id/$album->id\">Remove</a></td>";
        } else {
            echo "<td></td>";
        }
        echo "</tr>";
        ++$position;
    }?>
                </table>
            </div>
        </div>
    </main>

@endsection