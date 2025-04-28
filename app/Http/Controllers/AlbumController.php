<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Transformer\Template\Collection;


class AlbumController extends Controller
{
    /**
     * Shows albums whose titles match the search words.
     * 
     * @param \Illuminate\Http\Request $request Contains de keywords for searching and an integer for going forward or backwards through the pages.
     * 
     */
    public function search(Request $request): View
    {
        $search = $request->get('search');
        $iterator = $request->get('iterator');

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

    /**
     * Shows albums with the same tag (or similar) to the chosen one.
     * 
     * @param \Illuminate\Http\Request $request Contains the genre or tag keyword and an integer for going forward or backwards through the pages.
     * 
     */
    public function search_by_tag(Request $request): View
    {
        $search = $request->get('tag_name');
        $iterator = $request->get('iterator');


        $albums = DB::table('albums')
            ->join('artists', 'albums.artist_id', '=', 'artists.id')
            ->join('genres', 'albums.id', '=', 'genres.album_id')
            ->where('genres.name', '=', $search)
            ->select('albums.*', 'artists.name as artist_name')->limit(12)->get();

        $pages = intval(count($albums) / 12);

        $albums = $albums->skip(12 * ($iterator - 1))->take(12);

        return view('albums.search')->with('pages', $pages)->with('iterator', $iterator)->with('albums', $albums)->with('search', $search);

    }

    /**
     * Shows the detailed view of an album, with all its data, tracklist, reviews, tags, etc.
     * 
     * @param int $id The album id.
     * 
     * @param string $review_alert It will show a message if the album has been just uploaded.
     * 
     */
    public function show_album(int $id, string $review_alert = ""): RedirectResponse|View
    {
        $usertype = Auth()->user()->usertype;
        $user_id = Auth()->user()->id;
        $average_rating = DB::table('reviews')->where('album_id', '=', $id)->avg('rating');
        DB::table('albums')->where('albums.id', $id)->update(['average_rating' => $average_rating]);
        $album = DB::table('albums')->where('albums.id', $id)
            ->leftJoin('artists', 'albums.artist_id', '=', 'artists.id')
            ->select('albums.*', 'artists.name as artist_name', 'artists.registered as artist_registered', 'artists.user_id as artist_user_id')->first();
        // $genres = DB::table('genres')->where('genres.album_id', $id)->get();
        $genres = DB::table('genres')->where('genres.album_id', $id)->orderByraw('CHAR_LENGTH(genres.name) DESC')->get();

        $songs = DB::table('songs')->where('album_id', $id)->get();
        $reviews = DB::table('reviews')->where('album_id', $id)
            ->leftJoin('users', 'reviews.user_id', '=', 'users.id')
            ->select('reviews.*', 'users.name as user_name', 'users.profile_pic as user_profile_pic')
            ->orderBy('id', 'DESC')->get();
        $reviewed = DB::table('reviews')->where('user_id', $user_id)->where('album_id', $album->id)->first();
        $user_lists = DB::table('lists')->where('user_id', $user_id)->get();

        $recommended_albums = DB::table('albums')->limit(0);

        foreach ($genres as $genre) {
            // $recom_albums = DB::table('albums')->leftJoin('genres', 'albums.id', '=', 'genres.album_id')->where('genres.name', $genre->name)->whereNot('albums.id', $album->id)->select('albums.*')->limit(5)->get();
            $recom_albums = DB::table('albums')->leftJoin('genres', 'albums.id', '=', 'genres.album_id')->where('genres.name', $genre->name)->whereNot('albums.id', $album->id)->select('albums.*')->limit(5);
            $recommended_albums = $recommended_albums->union($recom_albums);
        }

        $recommended_albums = $recommended_albums->inRandomOrder()->limit(5)->get();
        // $recommended_albums = $recommended_albums->limit(5)->get();

        if ($usertype) {
            return view('albums.show')->with('recommended_albums', $recommended_albums)->with('album', $album)->with('genres', $genres)->with('songs', $songs)->with('reviews', $reviews)->with('user_lists', $user_lists)->with('review_alert', $review_alert)->with('reviewed', $reviewed);
        } else {
            return redirect()->back();
        }
    }

    /**
     * Shows the page where an user can search for an album that is not in the database for insert it (through an the Discogs API).
     */
    public function insert_album(): View
    {
        if (Auth()->user()->usertype === 'User' || Auth()->user()->usertype === 'Admin') {
            return view('albums.insert');
        }
        if (Auth()->user()->usertype === 'Artist') {
            $artist = DB::table('artists')->where('user_id', '=', Auth()->user()->id)->first()->name;
            return view('albums.insert_for_artists')->with('artist', $artist);
        } else {
            return redirect()->back();
        }
    }

    /**
     * Inserts the selected album from the Discogs API in the database and shows its detailed view.
     * 
     * @param \Illuminate\Http\Request $request Contains the album id in the Discogs API.
     * 
     */
    public function selected_album(Request $request): RedirectResponse|View
    {
        $album_api_id = $request->post('id');

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
        $artist_name = $album['artists'][0]['name'];
        $album_title = $album['title'];
        $cover = $album['images'][0]['uri'];
        $release_year = $album['year'];

        if (isset($album['artists'][0]['thumbnail_url'])) {
            $artist_pic = $album['artists'][0]['thumbnail_url'];
        } else {
            $artist_pic = 'https://www.onlinelogomaker.com/blog/wp-content/uploads/2017/06/music-logo-design.jpg';
        }

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

        if (!DB::table('albums')->where('name', $album_title)->first()) {
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

        return $this->show_album($album_id);
    }

    /**
     * Shows the view of an album where an user can edit its data, such as tracklist, genres, release year, etc.
     * 
     * @param int $id The album id.
     */
    public function edit_album($id): RedirectResponse|View
    {

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

    /**
     * Commits the changes after editing an album data.
     * 
     * @param \Illuminate\Http\Request $request Contains all the album data: id, name, release year, cover URL and the array with songs.
     * 
     */
    public function edited_album(Request $request): RedirectResponse|View
    {
        $id = $request->post('album_id');
        $name = $request->post('album_name');
        $release_year = $request->post('release_year');
        $cover = $request->post('cover');
        $songs = $request->post('songs');

        DB::table('albums')->where('id', $id)
            ->update(['name' => $name, 'cover' => $cover, 'release_year' => $release_year]);

        foreach ($songs as $song) {
            DB::table('songs')->where('id', $song[0])
                ->update(['name' => $song[1], 'length' => $song[2]]);
        }

        if ($request->post('new_genres') !== "") {
            $new_genres = $_POST['new_genres'];
            $new_genres = explode(", ", $new_genres);
            foreach ($new_genres as $new_genre) {
                $new_genre = ucwords($new_genre);
                DB::table('genres')->insert(['name' => $new_genre, 'album_id' => $id]);
            }
        }

        if (isset($_POST['delete_genres'])) {
            $delete_genres = $request->post('delete_genres');

            foreach ($delete_genres as $delete_genre) {
                DB::table('genres')->where('album_id', '=', $id)->where('name', '=', $delete_genre)->delete();
            }
        }

        return $this->show_album($id);
    }

    public function delete_album($id): RedirectResponse
    {
        if (Auth::user()->usertype == 'Admin') {
            DB::table('albums')->where('id', $id)->delete();
            return redirect()->back();
        } else {
            return redirect()->back();
        }
    }

}

