<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;


class HomeController extends Controller
{
    /**
     * Shows the user main page, depending on its usertype or role.
     * 
     * If the user is a standard user, it will show the most recent albums, reviews, and other newer data. 
     * If it's an admin it will show the main back office page. 
     * Lastly, if it's an artist, it will show a detailed view with featured reviews, lists, average rating of their albums, etc.
     */
    public function index(): RedirectResponse|View
    {
        if (Auth::id()) {
            $usertype = Auth()->user()->usertype;
            if ($usertype == 'User') {

                $genres = DB::table(table: 'genres')
                    ->select(DB::raw('genres.album_id, GROUP_CONCAT(genres.name SEPARATOR ", ") as "names"'))
                    ->groupBy(groups: 'genres.album_id');

                $albums = DB::table('albums')
                    ->join('artists', 'albums.artist_id', '=', 'artists.id')
                    ->joinSub($genres, 'genres', function ($join) {
                        $join->on('albums.id', '=', 'genres.album_id');
                    })
                    ->select('albums.*', 'artists.name as artist_name', 'genres.names as genres_names')
                    ->orderBy('albums.id', 'desc')->limit(6)->get();

                $top_10_albums = DB::table('albums')->orderBy('average_rating', 'desc')->limit(10)->get();

                $reviews = DB::table('reviews')
                    ->join('albums', 'reviews.album_id', '=', 'albums.id')
                    ->join('users', 'reviews.user_id', '=', 'users.id')
                    ->join('artists', 'albums.artist_id', '=', 'artists.id')
                    ->select('reviews.*', 'albums.name as album_name', 'albums.cover as cover', 'users.name as user_name', 'users.id as user_id', 'artists.name as artist_name', 'artists.id as artist_id')
                    ->orderBy('id', 'desc')->limit(7)->get();

                return view('users.start')->with('albums', $albums)->with('reviews', $reviews)->with('genres', $genres)->with('top_10_albums', $top_10_albums);
            } else if ($usertype == 'Admin') {
                return view('admins.start');
            } else if ($usertype == 'Artist' && Auth()->user()->blocked === 0) {
                $user = DB::table('users')
                    ->join('artists', 'users.id', '=', 'artists.user_id')
                    ->select('users.*', 'artists.name as artist_name', 'artists.description as artist_description', 'artists.info as artist_info')/**Hay que añadir la descripción y más datos */
                    ->where('artists.user_id', Auth()->user()->id)->first();

                $albums = DB::table('albums')
                    ->join('artists', 'albums.artist_id', '=', 'artists.id')
                    ->where('artists.user_id', $user->id)
                    ->select('albums.*', 'artists.name as artist_name')
                    ->orderBy('id', 'desc')->limit(5)->get();

                $reviews = DB::table('reviews')
                    ->join('albums', 'reviews.album_id', '=', 'albums.id')
                    ->join('users', 'reviews.user_id', '=', 'users.id')
                    ->join('artists', 'albums.artist_id', '=', 'artists.id')
                    ->where('artists.user_id', $user->id)
                    ->select('reviews.*', 'albums.name as album_name', 'albums.cover as cover', 'users.name as user_name', 'artists.name as artist_name', 'users.profile_pic as user_profile_pic')
                    ->orderBy('id', 'desc')->limit(5)->get();

                $lists = DB::table('lists')
                    ->join('lists_elements', 'lists.id', '=', 'lists_elements.list_id')
                    ->join('albums', 'lists_elements.album_id', '=', 'albums.id')
                    ->join('artists', 'albums.artist_id', '=', 'artists.id')
                    ->join('users', 'lists.user_id', '=', 'users.id')
                    ->where('artists.user_id', $user->id)
                    ->select('lists.*', 'users.name as user_name')
                    ->orderBy('lists.id', 'desc')->limit(5)->get();

                return view('artists.start')->with('user', $user)->with('albums', $albums)->with('reviews', $reviews)->with('lists', $lists);
            } else {
                return redirect()->back();
            }
        } else {
            // return redirect()->back();
            return view('home');
        }
    }
}
