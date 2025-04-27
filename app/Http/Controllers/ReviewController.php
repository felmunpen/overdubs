<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

use App\Models\Review;

class ReviewController extends AlbumController
{
    /**
     * Inserts a new review in the database, with rating, text and title.
     * 
     * @param \Illuminate\Http\Request $request Contains the review's information: album id, rating, text and title.
     * 
     */
    public function send_review(Request $request): RedirectResponse
    {

        $user_id = Auth()->user()->id;
        $album_id = $request->post('album_id');
        $rating = $request->post('rating');

        if (isset($_POST['review'])) {
            $review = $request->post('review');
        } else {
            $review = '';
        }

        if (isset($_POST['title'])) {
            $title = $request->post('title');
        } elseif (isset($_POST['review'])) {
            $title = substr($request->post('review'), 0, 10) . '...';
        } else {
            $title = '';
        }

        $review_alert = "";

        if (!DB::table('reviews')->where('user_id', $user_id)->where('album_id', $album_id)->first()) {
            DB::table('reviews')->insert(['id' => NULL, 'user_id' => Auth()->user()->id, 'title' => $title, 'album_id' => $album_id, 'text' => $review, 'rating' => $rating]);
            $review_alert = "Your review has been uploaded succesfully.";
            $new_average_rating = DB::table('reviews')->where('album_id', '=', $album_id)->average('rating');
            DB::table('albums')->where('id', $album_id)->update(['average_rating' => $new_average_rating]);

        } else {
            $review_alert = "Your review has not been uploaded succesfully. Please check if you have reviewed this album before.";
        }
        return redirect()->route('show_album', ['id' => $album_id])->with($album_id, $review_alert);

    }


    /**
     * Shows a detailed view of the selected review.
     * 
     * @param int $id The review identifier.
     */
    public function show_review($id): View
    {
        $review = DB::table('reviews')->where('reviews.id', '=', $id)
            ->join('albums', 'reviews.album_id', '=', 'albums.id')
            ->join('artists', 'albums.artist_id', '=', 'artists.id')
            ->join('users', 'reviews.user_id', '=', 'users.id')
            ->select('reviews.*', 'albums.cover as album_cover', 'albums.id as album_id', 'albums.name as album_name', 'artists.id as artist_id', 'artists.name as artist_name', 'users.id as user_id', 'users.name as user_name')
            ->first();

        $genres = DB::table('genres')->where('genres.album_id', $review->album_id)->orderByraw('CHAR_LENGTH(genres.name) DESC')->get();

        $recommended_albums = DB::table('albums')->limit(0);

        foreach ($genres as $genre) {
            // $recom_albums = DB::table('albums')->leftJoin('genres', 'albums.id', '=', 'genres.album_id')->where('genres.name', $genre->name)->whereNot('albums.id', $album->id)->select('albums.*')->limit(5)->get();
            $recom_albums = DB::table('albums')->leftJoin('genres', 'albums.id', '=', 'genres.album_id')->where('genres.name', $genre->name)->whereNot('albums.id', $review->album_id)->select('albums.*')->limit(5);
            $recommended_albums = $recommended_albums->union($recom_albums);
        }

        $recommended_albums = $recommended_albums->inRandomOrder()->limit(5)->get();
        // $recommended_albums = $recommended_albums->limit(5)->get();

        return view('reviews.show')->with('id', $id)->with('review', $review)->with('recommended_albums', $recommended_albums);
    }

    /**
     * Removes the selected review.
     * 
     * @param int $id The review identifier.
     */
    public function delete_review($id): RedirectResponse
    {
        $review = DB::table('reviews')->where('id', '=', $id)->first();

        if (Auth::user()->id === $review->user_id) {
            DB::table('reviews')->where('id', '=', $id)->delete();

        }

        return redirect()->back();
    }
}
