<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReviewController extends AlbumController
{
    //
    public function send_review()
    {
        $user_id = Auth()->user()->id;
        $album_id = $_POST['album_id'];
        $rating = $_POST['rating'];


        if (isset($_POST['review'])) {
            $review = $_POST['review'];
        } else {
            $review = '';
        }

        if (isset($_POST['title'])) {
            $title = $_POST['title'];
        } elseif (isset($_POST['review'])) {
            $title = substr($_POST['review'], 0, 10) . '...';
        } else {
            $title = '';
        }

        //He tenido que incluir esta variable en la función show_album del AlbumController
        $review_alert = "";

        if (!DB::table('reviews')->where('user_id', $user_id)->where('album_id', $album_id)->first()) {
            DB::table('reviews')->insert(['id' => NULL, 'user_id' => Auth()->user()->id, 'title' => $title, 'album_id' => $album_id, 'text' => $review, 'rating' => $rating]);
            $review_alert = "Your review has been uploaded succesfully.";

            //Actualizamos la nota media del album
            $sum = 0;
            $reviews = DB::table('reviews')->where('album_id', '=', $album_id)->get();
            $n = count($reviews);
            foreach ($reviews as $review) {
                $sum += $review->rating;
            }
            $new_average_rating = intval($sum / $n);
            DB::table('albums')->where('id', $album_id)->update(['average_rating' => $new_average_rating]);

        } else {
            $review_alert = "Your review has not been uploaded succesfully. Please check if you have reviewed this album before.";
        }
        return redirect()->route('show_album', ['id' => $album_id])->with($album_id, $review_alert);

    }

    public function show_review($id)
    {
        $review = DB::table('reviews')->where('reviews.id', '=', $id)
            ->join('albums', 'reviews.album_id', '=', 'albums.id')
            ->join('artists', 'albums.artist_id', '=', 'artists.id')
            ->join('users', 'reviews.user_id', '=', 'users.id')
            ->select('reviews.*', 'albums.cover as album_cover', 'albums.id as album_id', 'albums.name as album_name', 'artists.id as artist_id', 'artists.name as artist_name', 'users.id as user_id', 'users.name as user_name')
            ->first();

        return view('reviews.show')->with('id', $id)->with('review', $review);
    }

    public function delete_review($id)
    {
        $review = DB::table('reviews')->where('id', '=', $id)->first();

        if (Auth::user()->id === $review->user_id) {
            DB::table('reviews')->where('id', '=', $id)->delete();

        }

        return redirect()->back();
    }
}
