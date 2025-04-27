<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Blocks an user.
     * 
     * @param int $id It is the user id.
     * 
     * @return void
     */
    public function block($id): RedirectResponse
    {
        if (Auth()->user()->usertype === "Admin") {
            $user = DB::table('users')->where('id', $id)->first();
            if ($user->blocked) {
                DB::table('users')->where('id', $id)->update(['blocked' => 0]);
            } else {
                DB::table('users')->where('id', $id)->update(['blocked' => 1]);
            }
        }
        return redirect()->back();
    }

    /**
     * Shows all the registered users.
     */
    public function show_users(): View
    {
        $users = DB::table('users')->get();
        return view('admins.users')->with('users', $users);
    }

    /**
     * Shows all the albums in the database.
     */
    public function show_albums(): View
    {
        $albums = DB::table('albums')
            ->join('artists', 'albums.artist_id', '=', 'artists.id')
            ->select('albums.*', 'artists.name as artist_name')
            ->orderBy('id', 'desc')->get();

        return view('admins.albums')->with('albums', $albums);

    }

    /**
     * Shows all the artists in the database.
     */
    public function show_artists(): View
    {
        $artists = DB::table('artists')
            ->leftJoin('users', 'artists.user_id', '=', 'users.id')
            // ->join('artists', 'albums.artist_id', '=', 'artists.id')
            ->select('artists.*', 'users.name as user_name', 'users.year as year')
            ->orderBy('id', 'desc')->get();
        return view('admins.artists')->with('artists', $artists);
    }

    /**
     * Shows all the reviews made by users.
     */
    public function show_reviews(): View
    {
        $reviews = DB::table('reviews')
            ->join('users', 'reviews.user_id', '=', 'users.id')
            ->join('albums', 'reviews.album_id', '=', 'albums.id')
            ->join('artists', 'albums.artist_id', '=', 'artists.id')

            ->select('reviews.*', 'users.name as user_name', 'artists.name as artist_name', 'albums.name as album_name', 'albums.id as album_id')
            ->orderBy('id', 'DESC')->get();

        return view('admins.reviews')->with('reviews', $reviews);
    }

    /**
     * Shows a data report based on all the information stored in the database.
     */
    public function data_report(): View
    {
        $current_year = 2025;
        $users = DB::table('users')->get();
        $users_years = DB::table('users')->select('year')->orderBy('year', 'DESC')->get();
        $generations = array('Under 20' => 0, '20-30' => 0, '30-40' => 0, '40-50' => 0, '50-60' => 0, 'More than 60' => 0, );
        foreach ($users_years as $user) {
            $age = $current_year - $user->year;
            if ($age <= 20) {
                ++$generations['Under 20'];
            } elseif ($age > 20 && $age <= 30) {
                ++$generations['20-30'];
            } elseif ($age > 30 && $age <= 40) {
                ++$generations['30-40'];
            } elseif ($age > 40 && $age <= 50) {
                ++$generations['40-50'];
            } elseif ($age > 50 && $age <= 60) {
                ++$generations['50-60'];
            } elseif ($age > 60) {
                ++$generations['More than 60'];
            }
        }
        $users_avg_age = $current_year - intval(DB::table('users')->avg('year'));
        $users_by_gender = DB::table('users')->select(DB::raw('count(id) as user_count, gender'))->groupBy('gender')->orderBy('user_count', 'DESC')->get();
        $users_by_country = DB::table('users')->select(DB::raw('count(id) as user_count, country'))->groupBy('country')->orderBy('user_count', 'DESC')->limit(5)->get();
        $string_users_country_gender = array();
        foreach ($users_by_country as $iterator) {
            $n = $iterator->user_count;
            $females = count(DB::table('users')->where('country', '=', $iterator->country)->where('gender', '=', 'Female')->get());
            $males = count(DB::table('users')->where('country', '=', $iterator->country)->where('gender', '=', 'Male')->get());
            $others = count(DB::table('users')->where('country', '=', $iterator->country)->where('gender', '=', 'Other')->get());
            $string_users_country_gender[] = number_format(($females / $n * 100), 0) . "% females, " . number_format(($males / $n * 100), 0) . "% males, " . number_format(($others / $n * 100), 0) . "% other. <br>";

        }
        $albums = DB::table('albums')->get();
        $decades = array('1960s' => 0, '1970s' => 0, '1980s' => 0, '1990s' => 0, '2000s' => 0, '2010s' => 0, '2020s' => 0);
        foreach ($albums as $album) {
            if ($album->release_year >= 1960 && $album->release_year < 1970) {
                ++$decades['1960s'];
            } elseif ($album->release_year >= 1970 && $album->release_year < 1980) {
                ++$decades['1970s'];
            } elseif ($album->release_year >= 1980 && $album->release_year < 1990) {
                ++$decades['1980s'];
            } elseif ($album->release_year >= 1990 && $album->release_year < 2000) {
                ++$decades['1990s'];
            } elseif ($album->release_year >= 2000 && $album->release_year < 2010) {
                ++$decades['2000s'];
            } elseif ($album->release_year >= 2010 && $album->release_year < 2020) {
                ++$decades['2010s'];
            } elseif ($album->release_year >= 2020 && $album->release_year < 2030) {
                ++$decades['2020s'];
            }
        }
        $lists = DB::table('lists')->get();
        $avg_list_length = 0;
        foreach ($lists as $list) {
            $avg_list_length += DB::table('lists_elements')->where('list_id', '=', $list->id)->count();
        }
        $avg_list_length = intval($avg_list_length / count($lists));
        $reviews = DB::table('reviews')->get();
        $reviews_avg_rating = DB::table('reviews')->avg('rating');
        $avg_rating_females = DB::table('reviews')->join('users', 'users.id', '=', 'reviews.user_id')->where('users.gender', '=', 'Female')->avg('rating');
        $avg_rating_males = DB::table('reviews')->join('users', 'users.id', '=', 'reviews.user_id')->where('users.gender', '=', 'Male')->avg('rating');
        $avg_rating_others = DB::table('reviews')->join('users', 'users.id', '=', 'reviews.user_id')->where('users.gender', '=', 'Other')->avg('rating');
        $string_avg_rating_gender = "Average rating by females: " . number_format($avg_rating_females, 0) . ".<br>" . "Average rating by males: " . number_format($avg_rating_males, 0) . ".<br>" . "Average rating by others: " . number_format($avg_rating_others, 0) . ".<br>";
        $genres = DB::table('genres')->select(DB::raw('count(id) as genre_count, name'))->groupBy('name')->orderBy('genre_count', 'DESC')->limit(5)->get();
        $followings = DB::table('followings')->get();
        $mutuals = 0;
        foreach ($followings as $following) {
            foreach ($followings as $following_aux) {
                if ($following->follower_id === $following_aux->following_id && $following->following_id === $following_aux->follower_id) {
                    ++$mutuals;
                }
            }
        }
        $mutuals_percent = $mutuals / count($followings) * 100;
        $mutuals_percent = number_format($mutuals_percent, 0);

        return view('admins.data_report')
            ->with('users', $users)
            ->with('generations', $generations)
            ->with('users_avg_age', $users_avg_age)
            ->with('users_by_gender', $users_by_gender)
            ->with('users_by_country', $users_by_country)
            ->with('string_users_country_gender', $string_users_country_gender)
            ->with('albums', $albums)
            ->with('decades', $decades)
            ->with('lists', $lists)
            ->with('avg_list_length', $avg_list_length)
            ->with('reviews', $reviews)
            ->with('reviews_avg_rating', $reviews_avg_rating)
            ->with('string_avg_rating_gender', $string_avg_rating_gender)
            ->with('genres', $genres)
            ->with('followings', $followings)
            ->with('mutuals', $mutuals)
            ->with('mutuals_percent', $mutuals_percent);
    }
}
