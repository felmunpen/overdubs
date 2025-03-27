<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class AlbumController extends Controller
{
    //Probar a quitar la request
    public function search()
    {
        /*Pendiente de probar si es necesario Auth para impedir "invasiones"*/
        $search = $_GET['search'];
        $iterator = $_GET['iterator'];


        $albums_by_title = DB::table('albums')->where('albums.name', 'LIKE', '%' . $search . '%')
            ->leftJoin('artists', 'albums.artist_id', '=', 'artists.id')
            ->select('albums.*', 'artists.name as artist_name')->get();

        $albums_by_song = DB::table('songs')->where('songs.name', 'LIKE', '%' . $search . '%')
            ->rightJoin('albums', 'songs.album_id', '=', 'albums.id')
            ->join('artists', 'albums.artist_id', '=', 'artists.id')
            ->select('albums.*', 'artists.name as artist_name')->get();

        $albums_by_artist = DB::table('artists')->where('artists.name', 'LIKE', '%' . $search . '%')
            ->rightJoin('albums', 'artists.id', '=', 'albums.artist_id')
            ->select('albums.*', 'artists.name as artist_name')->get();

        $albums = $albums_by_title->merge($albums_by_song);
        $albums = $albums->merge($albums_by_artist);
        $albums = $albums->unique('id');

        $pages = intval(count($albums) / 12);

        $albums = $albums->skip(12 * ($iterator - 1))->take(12);


        return view('albums.search')->with('pages', $pages)->with('iterator', $iterator)->with('albums', $albums)->with('search', $search);
    }

    public function search_by_tag()
    {
        $search = $_GET['tag_name'];
        $iterator = $_GET['iterator'];


        $albums = DB::table('albums')
            ->join('artists', 'albums.artist_id', '=', 'artists.id')
            ->join('genres', 'albums.id', '=', 'genres.album_id')
            ->where('genres.name', '=', $search)
            ->select('albums.*', 'artists.name as artist_name')->limit(12)->get();

        $pages = intval(count($albums) / 12);

        $albums = $albums->skip(12 * ($iterator - 1))->take(12);

        return view('albums.search')->with('pages', $pages)->with('iterator', $iterator)->with('albums', $albums)->with('search', $search);

    }

    public function show_album(int $id, string $review_alert = "")
    {
        /*Pendiente de probar si es necesario Auth para impedir "invasiones" -> SÍ, LO ES*/
        // return view('users.user_show_album')->with('id', $id);
        $usertype = Auth()->user()->usertype;
        $user_id = Auth()->user()->id;

        $album = DB::table('albums')->where('albums.id', $id)
            ->leftJoin('artists', 'albums.artist_id', '=', 'artists.id')
            ->select('albums.*', 'artists.name as artist_name', 'artists.registered as artist_registered', 'artists.user_id as artist_user_id')->first();
        $genres = DB::table('genres')->where('genres.album_id', $id)->get();
        $songs = DB::table('songs')->where('album_id', $id)->get();
        $reviews = DB::table('reviews')->where('album_id', $id)
            ->leftJoin('users', 'reviews.user_id', '=', 'users.id')
            ->select('reviews.*', 'users.name as user_name', 'users.profile_pic as user_profile_pic')
            ->orderBy('id', 'DESC')->get();

        $reviewed = DB::table('reviews')->where('user_id', $user_id)->where('album_id', $album->id)->first();
        $user_lists = DB::table('lists')->where('user_id', $user_id)->get();

        if ($usertype) {
            // return view('users.show_album')->with('id', $id);
            return view('albums.show')->with('album', $album)->with('genres', $genres)->with('songs', $songs)->with('reviews', $reviews)->with('user_lists', $user_lists)->with('review_alert', $review_alert)->with('reviewed', $reviewed);
        } else {
            return redirect()->back();
        }
    }

    public function insert_album()
    {
        if (Auth()->user()->usertype) {
            return view('albums.insert');
        }
    }

    public function selected_album()
    {
        $album__id = 1;
        $album_api_id = $_POST['id'];

        $curl = curl_init();

        $key = "YwWCSQkRamXqBJPQSsxs";

        $secret = "cnJdnXAwAaUzhVDqgkzzBKKuCHFnaDFU";

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.discogs.com/releases/" . $album_api_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "user-agent: felmunpen_app"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $album = json_decode($response, true);
        // $title = $album['title'];
        $artist_name = $album['artists'][0]['name'];
        $album_title = $album['title'];
        $cover = $album['images'][0]['uri'];
        $release_year = $album['year'];

        if (isset($album['artists'][0]['thumbnail_url'])) {
            $artist_pic = $album['artists'][0]['thumbnail_url'];
        } else {
            $artist_pic = 'https://www.onlinelogomaker.com/blog/wp-content/uploads/2017/06/music-logo-design.jpg';
        }

        //Hay que LIMPIAR los nombres de artistas de discogs
        $pattern = "/ \([0-9]*\)/";
        $artist_name = preg_replace($pattern, "", $artist_name);
        $artist_id = $album['artists'][0]['id'];

        if (!DB::table('artists')->where('name', $artist_name)->first()) {

            $curl_2 = curl_init();

            curl_setopt_array($curl_2, array(
                CURLOPT_URL => "https://api.discogs.com/artists/" . $artist_id,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "user-agent: felmunpen_app"
                ),
            ));

            $response_2 = curl_exec($curl_2);
            $err_2 = curl_error($curl_2);

            $artist = json_decode($response_2, true);

            curl_close($curl_2);

            $description = $artist['profile'];
            $pattern_2 = "/[a-z][0-9]+/";
            $pattern_3 = "/[a-z]=[0-9]+/";
            $pattern_4 = "/[a-z]=/";

            $description = preg_replace($pattern, "", $description);
            $description = preg_replace($pattern_2, "", $description);
            $description = preg_replace($pattern_3, "", $description);
            $description = preg_replace($pattern_4, "", $description);

            $description = str_replace("[", "", $description);
            $description = str_replace("]", "", $description);

            if (strlen($description) > 1000) {
                $description = substr($description, 0, 900) . "...";

            }

            DB::table('artists')->insert(['id' => NULL, 'name' => $artist_name, 'registered' => 0, 'artist_pic' => $artist_pic, 'user_id' => NULL, 'description' => $description]);
        }

        $artist_id = DB::table('artists')->where('name', $artist_name)->first()->id;

        //Tal y como está no podrían existir dos álbumes con el mismo nombre
        if (!DB::table('albums')->where('name', $album_title)->first()) {
            // DB::table('albums')->insert(['id' => NULL, 'name' => $album_title, 'artist_id' => $artist_id, 'cover' => $cover, 'release_year' => $release_year, 'genres_id' => NULL]);
            DB::table('albums')->insert(['id' => NULL, 'name' => $album_title, 'artist_id' => $artist_id, 'cover' => $cover, 'release_year' => $release_year]);
        }

        $album_id = DB::table('albums')->where('name', $album_title)->first()->id;

        $tracklist = $album['tracklist'];
        $number_of_songs = count($album['tracklist']);

        $songs = [];
        for ($i = 0; $i < $number_of_songs; $i++) {
            array_push($songs, ['id' => NULL, 'album_id' => $album_id, 'name' => $tracklist[$i]['title'], 'number' => $i + 1, 'length' => $tracklist[$i]['duration']]);
        }
        DB::table('songs')->insert($songs);


        $genres = [];
        $number_of_genres = count($album['genres']);
        for ($j = 0; $j < $number_of_genres; $j++) {
            array_push($genres, ['id' => NULL, 'name' => $album['genres'][$j], 'album_id' => $album_id]);

        }

        $number_of_styles = count($album['styles']);
        for ($j = 0; $j < $number_of_styles; $j++) {
            array_push($genres, ['id' => NULL, 'name' => $album['styles'][$j], 'album_id' => $album_id]);
        }

        DB::table('genres')->insert($genres);

        // return view('albums.selected')->with('album_id', $album_id)->with('title', $title)->with('artist_name', $artist_name);
        return $this->show_album($album_id);
    }

    public function edit_album($id)
    {
        // $album = DB::table('albums')
        //     ->leftJoin('artists', 'albums.id', '=', 'artists.id')
        //     ->select('albums.*', 'artists.name as artist_name')->where('albums.id', $id)
        //     ->first();
        // $album = DB::table('albums')
        //     ->leftJoin('artists', 'albums.id', '=', 'artists.id')
        //     ->select('albums.*', 'artists.name as artist_name')->where('albums.id', $id)
        //     ->first();
        // $artist = DB::table('artists')
        //     ->select('artists.*')->where('artists.id', $album->artist_id)
        //     ->first();
        // if (Auth()->user()->usertype) {
        //     return view('albums.edit')->with('album', $album)->with('artist', $artist);
        // }

        $album = DB::table('albums')->where('albums.id', $id)
            ->leftJoin('artists', 'albums.artist_id', '=', 'artists.id')
            ->select('albums.*', 'artists.name as artist_name', 'artists.registered as artist_registered', 'artists.user_id as artist_user_id')->first();
        $genres = DB::table('genres')->where('genres.album_id', $id)->get();
        $songs = DB::table('songs')->where('album_id', $id)->get();
        $reviews = DB::table('reviews')->where('album_id', $id)
            ->leftJoin('users', 'reviews.user_id', '=', 'users.id')
            ->select('reviews.*', 'users.name as user_name', 'users.profile_pic as user_profile_pic')
            ->orderBy('id', 'DESC')->get();

        if (($album->artist_registered === 0 || Auth()->user()->id === $album->artist_user_id || Auth()->user()->usertype === "Admin") && Auth()->user()->blocked === 0) {
            return view('albums.edit')->with('album', $album)->with('genres', $genres)->with('songs', $songs)->with('reviews', $reviews);
        } else {
            return redirect()->back();
        }

    }

    public function edited_album()
    {
        $id = $_POST['album_id'];
        $name = $_POST['album_name'];
        $release_year = $_POST['release_year'];
        $cover = $_POST['cover'];
        $songs = $_POST['songs'];



        // $genre = $_POST['genre'];

        DB::table('albums')->where('id', $id)
            ->update(['name' => $name, 'cover' => $cover, 'release_year' => $release_year]);

        foreach ($songs as $song) {
            DB::table('songs')->where('id', $song[0])
                ->update(['name' => $song[1], 'length' => $song[2]]);
        }

        if ($_POST['new_genres'] !== "") {


            $new_genres = $_POST['new_genres'];

            $new_genres = explode(", ", $new_genres);

            foreach ($new_genres as $new_genre) {
                $new_genre = ucwords($new_genre);
                DB::table('genres')->insert(['name' => $new_genre, 'album_id' => $id]);
            }
        }

        if (isset($_POST['delete_genres'])) {
            $delete_genres = $_POST['delete_genres'];

            foreach ($delete_genres as $delete_genre) {
                DB::table('genres')->where('album_id', '=', $id)->where('name', '=', $delete_genre)->delete();
            }
        }


        return $this->show_album($id);
        // if (Auth()->user()->usertype) {
        //     return $this->show_album($id);
        // }
    }

}

