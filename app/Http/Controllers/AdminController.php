<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;


use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    public function redirect($element)
    {
        /*Pendiente de probar si es necesario Auth para impedir "invasiones"*/
        // if (Auth()->user()->usertype === "Admin") {
        //     if ($element === 'users')
        //         return view('admins.users');
        //     elseif ($element === 'albums') {
        //         return view('admins.albums');
        //     } elseif ($element === 'reviews') {
        //         return view('admins.reviews');
        //     } elseif ($element === 'data_report') {
        //         return view('admins.data_report');
        //     } else {
        //         return redirect()->back();
        //     }
        // }
    }

    public function block($id)
    {
        /*Pendiente de probar si es necesario Auth para impedir "invasiones"*/
        if (Auth()->user()->usertype === "Admin") {

            $user = DB::table('users')->where('id', $id)->first();
            #(DES)BLOQUEALO GUACHO
            if ($user->blocked) {
                DB::table('users')->where('id', $id)->update(['blocked' => 0]);
            } else {
                DB::table('users')->where('id', $id)->update(['blocked' => 1]);
            }

            return redirect()->back();
        }
    }

    public function show_users()
    {
        $users = DB::table('users')->get();

        return view('admins.users')->with('users', $users);

    }

    public function show_albums()
    {
        $albums = DB::table('albums')
            ->join('artists', 'albums.artist_id', '=', 'artists.id')
            ->select('albums.*', 'artists.name as artist_name')
            ->orderBy('id', 'desc')->get();

        return view('admins.albums')->with('albums', $albums);

    }

    public function show_artists()
    {
        $artists = DB::table('artists')
            ->leftJoin('users', 'artists.user_id', '=', 'users.id')
            // ->join('artists', 'albums.artist_id', '=', 'artists.id')
            ->select('artists.*', 'users.name as user_name', 'users.year as year')
            ->orderBy('id', 'desc')->get();

        // $artists_wo_users = DB::table('artists')
        //     ->where('registered', 0)
        //     ->orderBy('id', 'desc')->get();

        // $artists = $artists_w_user->merge($artists_wo_users);



        return view('admins.artists')->with('artists', $artists);

    }

    public function show_reviews()
    {
        $reviews = DB::table('reviews')
            ->join('users', 'reviews.user_id', '=', 'users.id')
            ->join('albums', 'reviews.album_id', '=', 'albums.id')
            ->join('artists', 'albums.artist_id', '=', 'artists.id')

            ->select('reviews.*', 'users.name as user_name', 'artists.name as artist_name', 'albums.name as album_name', 'albums.id as album_id')
            ->orderBy('id', 'DESC')->get();

        return view('admins.reviews')->with('reviews', $reviews);

    }
}
