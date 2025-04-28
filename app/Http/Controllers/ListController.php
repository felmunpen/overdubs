<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;


class ListController extends Controller
{
    /**
     * Creates a list with the name and picture provided in a form.
     * 
     * @param \Illuminate\Http\Request $request Contains two strings: the name and cover URL for the list.
     * 
     */
    public function created_list(Request $request): RedirectResponse
    {
        $user_id = Auth()->user()->id;

        $list_name = $request->post('list_name');
        if ($request->post('list_pic')) {
            $list_pic = $request->post('list_pic');
        } else {
            $list_pic = 'https://static.vecteezy.com/system/resources/previews/049/624/353/non_2x/party-playlist-icon-design-vector.jpg';
        }

        DB::table('lists')->insert(['id' => NULL, 'user_id' => $user_id, 'name' => $list_name, 'list_pic' => $list_pic]);

        return redirect()->back();
    }

    /**
     * Shows a list.
     * 
     * @param int $id The list id.
     */
    public function show_list($id): View
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

    /**
     * Allows an user to edit one of its lists.
     * 
     * @param int $id The list id.
     */
    public function edit_list($id): RedirectResponse|View
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

    /**
     * Commits the changes to the list.
     * 
     * @param \Illuminate\Http\Request $request Contains an integer with the list identifier.
     * 
     */
    public function edited_list(Request $request): View
    {
        $id = $request->post('list_id');

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

    /**
     * Allows an user to delete one of its lists.
     * 
     * @param int $id It is the list identifier.
     */
    public function delete_list($id): RedirectResponse
    {
        $list = DB::table('lists')->where('id', '=', $id);

        if (Auth::user()->id === $list->first()->user_id) {
            $list->limit(1)->delete();
        }

        return redirect()->route('show_profile');

    }

    /**
     * Allows an user to add an album to any its lists (previosly created).
     * 
     * @param \Illuminate\Http\Request $request Contains two integers: the list id and the album id.
     *
     */
    public function add_to_list(Request $request): RedirectResponse
    {
        $list_id = $request->post('list_id');
        $album_id = $request->post('album_id');

        if (!DB::table('lists_elements')->where('list_id', $list_id)->where('album_id', $album_id)->first()) {
            DB::table('lists_elements')->insert(['id' => NULL, 'list_id' => $list_id, 'album_id' => $album_id]);
        }
        return redirect()->back();

    }

    /**
     * Removes an album of a list.
     * 
     * @param int $list_id List identifier.
     * 
     * @param int $element_id Identifier of the album in the list.
     */
    public function remove_from_list($list_id, $element_id): RedirectResponse
    {
        $list = DB::table('lists')->where('id', '=', $list_id)->first();

        if (Auth::user()->id === $list->user_id) {
            DB::table('lists_elements')->where('id', '=', $element_id)->where('list_id', '=', $list_id)->delete();
        }
        return redirect()->back();

    }
}
