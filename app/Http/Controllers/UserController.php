<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //


    public function show_profile()
    {
        $id = Auth::user()->id;
        $user = DB::table('users')->where('id', $id)->first();

        if (Auth::user()->usertype === 'User') {

            $lists = DB::table('lists')->where('user_id', $id)->get();//Necesito el nombre de la lista
            $reviews = DB::table('reviews')->where('user_id', $id)
                ->join('albums', 'albums.id', '=', 'reviews.album_id')
                ->select('reviews.*', 'albums.name as album_name', 'albums.cover as cover', 'albums.id as album_id')->get();

            $followings = DB::table('followings')->where('follower_id', $id)
                ->join('users', 'users.id', '=', 'followings.following_id')
                ->select('followings.*', 'users.name as user_name', 'users.profile_pic as profile_pic')->get();

            $followers = DB::table('followings')->where('following_id', $id)
                ->join('users', 'users.id', '=', 'followings.follower_id')
                ->select('followings.*', 'users.name as user_name', 'users.profile_pic as profile_pic')->get();

            $messages_to = DB::table('messages')
                ->join('users', 'messages.receiver_id', '=', 'users.id')
                ->where('messages.sender_id', '=', $id)
                ->select('messages.*', 'users.name as user_name', 'users.profile_pic as profile_pic')->orderBy('id', 'desc')->get();

            $messages_from = DB::table('messages')
                ->join('users', 'messages.sender_id', '=', 'users.id')
                ->where('messages.receiver_id', '=', $id)
                ->select('messages.*', 'users.name as user_name', 'users.profile_pic as profile_pic')->orderBy('id', 'desc')->get();

            return view('profile.show_profile')->with('user', $user)->with('lists', $lists)->with('reviews', $reviews)->with('followings', $followings)->with('followers', $followers)->with('messages_to', $messages_to)->with('messages_from', $messages_from);
        } elseif (Auth::user()->usertype === 'Artist') {
            $artist = DB::table('artists')->where('user_id', $id)->first();


            $followers = DB::table('followings')->where('following_id', $id)
                ->join('users', 'users.id', '=', 'followings.follower_id')
                ->select('followings.*', 'users.name as user_name', 'users.profile_pic as profile_pic')->get();

            return view('profile.show_profile')->with('user', $user)->with('artist', $artist)->with('followers', $followers);

        } elseif (Auth::user()->usertype === 'Admin') {
            $lists = DB::table('lists')->where('user_id', $id)->get();//Necesito el nombre de la lista

            $followings = DB::table('followings')->where('follower_id', $id)
                ->join('users', 'users.id', '=', 'followings.following_id')
                ->select('followings.*', 'users.name as user_name', 'users.profile_pic as profile_pic')->get();

            $followers = DB::table('followings')->where('following_id', $id)
                ->join('users', 'users.id', '=', 'followings.follower_id')
                ->select('followings.*', 'users.name as user_name', 'users.profile_pic as profile_pic')->get();


            $messages_to = DB::table('messages')
                ->join('users', 'messages.receiver_id', '=', 'users.id')
                ->where('messages.sender_id', '=', $id)
                ->select('messages.*', 'users.name as user_name', 'users.profile_pic as profile_pic')->orderBy('id', 'desc')->get();

            $messages_from = DB::table('messages')
                ->join('users', 'messages.sender_id', '=', 'users.id')
                ->where('messages.receiver_id', '=', $id)
                ->select('messages.*', 'users.name as user_name', 'users.profile_pic as profile_pic')->orderBy('id', 'desc')->get();

            return view('profile.show_profile')->with('user', $user)->with('followings', $followings)->with('followers', $followers)->with('messages_to', $messages_to)->with('messages_from', $messages_from)->with('lists', $lists);

        }
    }

    public function show_user($id)
    {
        $user = DB::table('users')->where('id', $id)->first();

        $follow = true;

        if ($user->usertype === 'User' || $user->usertype === 'Admin') {

            if (DB::table('followings')->where([['follower_id', '=', Auth::user()->id], ['following_id', '=', $id]])->first()) {
                $follow = true;
            } else {
                $follow = false;
            }

            /****************/

            // $albums = DB::table(table: 'lists_elements')
            //     // ->join('lists_elements', 'album.id', '=', 'lists_elements.album_id')
            //     ->select(DB::raw('lists_elements.list_id, GROUP_CONCAT(lists_elements.album_id SEPARATOR ", ") as "ids"'))
            //     ->groupBy(groups: 'lists_elements.list_id');

            // $albums = DB::table('albums')
            //     ->join('artists', 'albums.artist_id', '=', 'artists.id')
            //     ->joinSub($genres, 'genres', function ($join) {
            //         $join->on('albums.id', '=', 'genres.album_id');
            //     })
            //     ->select('albums.*', 'artists.name as artist_name', 'genres.names as genres_names')
            //     ->orderBy('albums.id', 'desc')->limit(6)->get();

            /****************/

            $lists = DB::table('lists')->where('user_id', $id)->get();

            /****************/

            $reviews = DB::table('reviews')->where('user_id', $id)
                ->join('albums', 'albums.id', '=', 'reviews.album_id')
                ->select('reviews.*', 'albums.name as album_name', 'albums.cover as cover', 'albums.id as album_id')->get();

            $followings = DB::table('followings')->where('follower_id', $id)
                ->join('users', 'users.id', '=', 'followings.following_id')
                ->select('followings.*', 'users.name as user_name', 'users.profile_pic as profile_pic')->get();

            $followers = DB::table('followings')->where('following_id', $id)
                ->join('users', 'users.id', '=', 'followings.follower_id')
                ->select('followings.*', 'users.name as user_name', 'users.profile_pic as profile_pic')->get();

            // return view('users.show')->with('user', $user)->with('lists', $lists)->with('reviews', $reviews)->with('followings', $followings)->with('followers', $followers)->with('follow', $follow)->with('albums_in_lists', $albums_in_lists);

            /*****************************/


            return view('users.show')->with('user', $user)->with('lists', $lists)->with('reviews', $reviews)->with('followings', $followings)->with('followers', $followers)->with('follow', $follow);

            /*****************************/
        }

        // return view('users.show')->with('user', $user)->with('lists', $lists)->with('reviews', $reviews)->with('followings', $followings)->with('followers', $followers)->with('follow', $follow);
        elseif ($user->usertype === 'Artist') {

            $description = DB::table('artists')
                ->leftJoin('users', 'artists.user_id', '=', 'users.id')
                // ->join('artists', 'albums.artist_id', '=', 'artists.id')
                ->select('artists.*', 'users.name as user_name', 'users.year as year')
                ->orderBy('id', 'desc')->get();

            // $lists = DB::table('lists')->where('user_id', $id)->get();//Necesito el nombre de la lista
            // $reviews = DB::table('reviews')->where('user_id', $id)
            //     ->join('albums', 'albums.id', '=', 'reviews.album_id')
            //     ->select('reviews.*', 'albums.name as album_name', 'albums.cover as cover')->get();

            // $followings = DB::table('followings')->where('follower_id', $id)
            //     ->join('users', 'users.id', '=', 'followings.following_id')
            //     ->select('followings.*', 'users.name as user_name', 'users.profile_pic as profile_pic')->get();

            // $followers = DB::table('followings')->where('following_id', $id)
            //     ->join('users', 'users.id', '=', 'followings.follower_id')
            //     ->select('followings.*', 'users.name as user_name', 'users.profile_pic as profile_pic')->get();

            return view('users.show')->with('user', $user);

        }

    }

    public function follow_or_unfollow()
    {
        $follow = $_POST['follow'];
        $following_id = $_POST['following_id'];

        if ($follow) {
            DB::table('followings')->where([
                'follower_id' => Auth::user()->id,
                'following_id' => $following_id
            ])->delete();
        } else {
            DB::table('followings')->insert([
                'follower_id' => Auth::user()->id,
                'following_id' => $following_id
            ]);
        }

        return redirect()->back();
    }

    // public function send_message()
    // {
    //     $receiver_id = $_POST['receiver_id'];
    //     $text = $_POST['message'];

    //     $receiver = DB::table('users')->where('id', '=', $receiver_id)->first();

    //     if ((Auth::user()->usertype !== 'Artist') && $receiver->usertype !== 'Artist') {
    //         DB::table('messages')->insert(['sender_id' => Auth::user()->id, 'receiver_id' => $receiver_id, 'text' => $text]);
    //     }

    //     return redirect()->back();
    // }

    public function update_bio()
    {
        $id = Auth::user()->id;
        $bio = $_POST['bio'];

        DB::table('users')->where('id', $id)->update(['bio' => $bio]);
        return redirect()->back();
    }
}
