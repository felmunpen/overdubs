<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{

    /**
     * Shows the user page, with personal data, follows, reviews, ratings, lists, etc.
     * 
     * Shows the page that is the main hub of the user. Its content will be different depending on the user's role or type.
     */
    public function show_profile(): View
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

        } else {
            return redirect()->back();
        }
    }

    /**
     * Shows an user public page.
     * 
     * This page will be nearly identical for users and admins. However, if it is an artist it will show other information, as artists don't create lists, or write reviews, etc.
     * 
     * @param int $id The user identifier.
     */
    public function show_user($id): View
    {
        $user = DB::table('users')->where('id', $id)->first();

        $follow = true;

        if ($user->usertype === 'User' || $user->usertype === 'Admin') {

            if (DB::table('followings')->where([['follower_id', '=', Auth::user()->id], ['following_id', '=', $id]])->first()) {
                $follow = true;
            } else {
                $follow = false;
            }

            $lists = DB::table('lists')->where('user_id', $id)->get();

            $reviews = DB::table('reviews')->where('user_id', $id)
                ->join('albums', 'albums.id', '=', 'reviews.album_id')
                ->select('reviews.*', 'albums.name as album_name', 'albums.cover as cover', 'albums.id as album_id')->get();

            $followings = DB::table('followings')->where('follower_id', $id)
                ->join('users', 'users.id', '=', 'followings.following_id')
                ->select('followings.*', 'users.name as user_name', 'users.profile_pic as profile_pic')->get();

            $followers = DB::table('followings')->where('following_id', $id)
                ->join('users', 'users.id', '=', 'followings.follower_id')
                ->select('followings.*', 'users.name as user_name', 'users.profile_pic as profile_pic')->get();

            return view('users.show')->with('user', $user)->with('lists', $lists)->with('reviews', $reviews)->with('followings', $followings)->with('followers', $followers)->with('follow', $follow);

        } elseif ($user->usertype === 'Artist') {

            $description = DB::table('artists')
                ->leftJoin('users', 'artists.user_id', '=', 'users.id')
                ->select('artists.*', 'users.name as user_name', 'users.year as year')
                ->orderBy('id', 'desc')->get();

            return view('users.show')->with('user', $user);
        } else {
            return redirect()->back();
        }
    }

    /**
     * Allows an user to follow or unfollow another one.
     * 
     * @param \Illuminate\Http\Request $request Contains a boolean to determine if the function works to "follow" or to "unfollow" and the other user's identifier.
     * 
     */
    public function follow_or_unfollow(Request $request): RedirectResponse
    {
        $follow = $request->post('follow');
        $following_id = $request->post('following_id');

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

    /**
     * Allows an to update his/her/its bio.
     * 
     * @param \Illuminate\Http\Request $request Contains the new bio text.
     * 
     */
    public function update_bio(Request $request): RedirectResponse
    {
        $id = Auth::user()->id;
        $bio = $request->post('bio');

        DB::table('users')->where('id', $id)->update(['bio' => $bio]);
        return redirect()->back();
    }
}
