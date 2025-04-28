<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

use Illuminate\Http\Request;

class ArtistController extends Controller
{
    /**
     * Allows a registered artist to change its description.
     * 
     * @param \Illuminate\Http\Request $request Contains the text with the description.
     */
    public function update_description(Request $request): RedirectResponse
    {
        $id = Auth::user()->id;
        $description = $request->post('description');

        DB::table('artists')->where('user_id', $id)->update(['description' => $description]);
        DB::table('users')->where('id', $id)->update(['bio' => $description]);

        return redirect()->back();
    }

    /**
     * Allows a registered artist to change its info, where they can write about upcoming albums, shows, etc.
     * 
     * @param \Illuminate\Http\Request $request Contains the text with the info.
     * 
     */
    public function update_info(Request $request): RedirectResponse
    {
        $id = Auth::user()->id;
        $info = $request->post('info');

        DB::table('artists')->where('user_id', $id)->update(['info' => $info]);
        return redirect()->back();
    }

    /**
     * Shows an artist page.
     * 
     * @param int $id The artist id (from the 'artists' table).
     */
    public function show_artist($id): View
    {
        $artist = DB::table('artists')->where('id', $id)->first();

        $albums = DB::table('albums')
            ->where('artist_id', $id)
            ->select('albums.*')
            ->orderBy('id', 'desc')->get();

        return view('artists.show')->with('artist', $artist)->with('albums', $albums);
    }
}
