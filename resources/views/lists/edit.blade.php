@extends('layouts.common')

@section('titulo', 'Inicio')

@section('contenido')

    <main>
        <form class="show_list_card" method="POST" action="{{ route('edited_list') }}">
            @csrf
            <input type="hidden" name="list_id" value="<?php echo $list->id ?>">

            <div>
                <h3>
                    <input type="text" class="input_text_slim" name="list_name" placeholder="<?php echo $list->name ?>">
                </h3>
                <input type="text" class="input_text_slim" name="list_pic_url" placeholder="List cover URL."
                    style="margin-top: 0.2vh; margin-bottom: 1.2vh; width: 100%;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; column-gap: 1vh;">
                    <img src="<?php echo $list->list_pic ?>" class="list_cover">
                    <div>
                        <span style="font-style: italic;">by<br><a style="font-size: larger;"
                                href="/show_user/<?php echo $list->user_id?>">
                                <?php echo $list->user_name?>
                            </a></span><br><br>
                        @if (Auth::user()->id === $list->user_id)
                            <input type="submit" name="apply_changes" value="Apply changes">
                            <br><br>
                            <a href="/delete_list/<?php    echo $list->id?>" class="link_button">Delete list</a>
                        @endif
                    </div>
                </div>

            </div>
            <div class="static_card" style="padding: 2vh 4vh; border: thin solid var(--intense);">
                <table class="list_table">

                    <tr>
                        <th></th>
                        <th>Cover</th>
                        <th>Album</th>
                        <th>Artist</th>
                        @if(Auth::user()->id === $list->user_id)
                            <th></th>
                        @endif
                    </tr>
                    <?php $position = 1;
    foreach ($albums as $album) {
        echo "<tr><td>$position</td><td><img src=\"$album->album_cover\"></td><td><a href=\"/show_album/$album->album_id\">$album->album_name</a></td><td><a href=\"/show_artist/$album->artist_id\">$album->artist_name</a></td>";
        if (Auth::user()->id === $list->user_id) {
            echo "<td><a href=\"/remove_from_list/$list->id/$album->id\">Remove</a></td>";
        }
        echo "</tr>";
        ++$position;
    }?>
                </table>
            </div>
            </div>
        </form>
    </main>

@endsection