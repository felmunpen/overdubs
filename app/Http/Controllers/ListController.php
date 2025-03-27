<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class ListController extends Controller
{
    //
    public function created_list()
    {
        $user_id = Auth()->user()->id;
        $list_name = $_POST['list_name'];
        $list_pic = $_POST['list_pic'];
        if ($_POST['list_pic']) {
            $list_pic = $_POST['list_pic'];
        } else {
            $list_pic = 'https://isobarscience.com/wp-content/uploads/2020/09/default-profile-picture1.jpg';
        }
        DB::table('lists')->insert(['id' => NULL, 'user_id' => $user_id, 'name' => $list_name, 'list_pic' => $list_pic]);

        // return view('profile.show_profile');
        return redirect()->back();

    }

    public function show_list($id)
    {
        $list = DB::table(table: 'lists')
            ->join('users', 'lists.user_id', '=', 'users.id')
            ->select('lists.*', 'users.name as user_name', 'users.id as user_id')
            ->where('lists.id', '=', $id)->first();
        $albums = DB::table('lists_elements')
            ->join('albums', 'lists_elements.album_id', '=', 'albums.id')
            ->join('artists', 'albums.artist_id', '=', 'artists.id')
            ->select('lists_elements.*', 'albums.id as album_id', 'albums.name as album_name', 'albums.cover as album_cover', 'artists.id as artist_id', 'artists.name as artist_name')
            ->where('list_id', '=', $id)->get();
        return view('lists.show')->with('list', $list)->with('albums', $albums);
    }
    public function edit_list($id)
    {
        $list_creator = DB::table(table: 'lists')->join('users', 'lists.user_id', '=', 'users.id')
            ->where('lists.id', '=', $id)
            ->select('lists.*', 'users.id as user_id')->first();

        if (Auth::user()->id === $list_creator->user_id) {
            $list = DB::table(table: 'lists')
                ->join('users', 'lists.user_id', '=', 'users.id')
                ->select('lists.*', 'users.name as user_name', 'users.id as user_id')
                ->where('lists.id', '=', $id)->first();

            $albums = DB::table('lists_elements')
                ->join('albums', 'lists_elements.album_id', '=', 'albums.id')
                ->join('artists', 'albums.artist_id', '=', 'artists.id')
                ->select('lists_elements.*', 'albums.id as album_id', 'albums.name as album_name', 'albums.cover as album_cover', 'artists.id as artist_id', 'artists.name as artist_name')
                ->where('list_id', '=', $id)->get();

            return view('lists.edit')->with('list', $list)->with('albums', $albums);

        } else {
            return redirect()->back();
        }
    }

    public function edited_list()
    {
        $id = $_POST['list_id'];

        if ($_POST['list_name'] !== "") {
            $list_name = $_POST['list_name'];
            DB::table('lists')->where('id', '=', $id)->update(["name" => $list_name]);
        }

        if ($_POST['list_pic_url'] !== "") {
            $list_pic_url = $_POST['list_pic_url'];
            DB::table('lists')->where('id', '=', $id)->update(["list_pic" => $list_pic_url]);
        }

        $list = DB::table(table: 'lists')
            ->join('users', 'lists.user_id', '=', 'users.id')
            ->select('lists.*', 'users.name as user_name', 'users.id as user_id')
            ->where('lists.id', '=', $id)->first();
        $albums = DB::table('lists_elements')
            ->join('albums', 'lists_elements.album_id', '=', 'albums.id')
            ->join('artists', 'albums.artist_id', '=', 'artists.id')
            ->select('lists_elements.*', 'albums.id as album_id', 'albums.name as album_name', 'albums.cover as album_cover', 'artists.id as artist_id', 'artists.name as artist_name')
            ->where('list_id', '=', $id)->get();
        return view('lists.show')->with('list', $list)->with('albums', $albums);

    }

    public function delete_list($id)
    {
        $list = DB::table('lists')->where('id', '=', $id)->delete();


        return redirect()->back();
    }

    public function add_to_list()
    {
        $list_id = $_POST['list_id'];
        $album_id = $_POST['album_id'];
        if (!DB::table('lists_elements')->where('list_id', $list_id)->where('album_id', $album_id)->first()) {
            DB::table('lists_elements')->insert(['id' => NULL, 'list_id' => $list_id, 'album_id' => $album_id]);
        }
        return redirect()->back();

    }

    public function remove_from_list($list_id, $element_id)
    {
        $list = DB::table('lists')->where('id', '=', $list_id)->first();

        if (Auth::user()->id === $list->user_id) {
            DB::table('lists_elements')->where('id', '=', $element_id)->where('list_id', '=', $list_id)->delete();
        }
        return redirect()->back();

    }
}
