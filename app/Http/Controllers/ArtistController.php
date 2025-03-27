<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class ArtistController extends Controller
{
    //
    public function update_description()
    {
        $id = Auth::user()->id;
        $description = $_POST['description'];

        DB::table('artists')->where('user_id', $id)->update(['description' => $description]);
        DB::table('users')->where('id', $id)->update(['bio' => $description]);

        return redirect()->back();
    }

    /*Hay que actualizar esta función cuando info esté en la DB*/
    public function update_info()
    {
        $id = Auth::user()->id;
        $info = $_POST['info'];

        DB::table('artists')->where('user_id', $id)->update(['info' => $info]);
        return redirect()->back();
    }

    public function show_artist($id)
    {
        $artist = DB::table('artists')->where('id', $id)->first();

        $albums = DB::table('albums')
            // ->join('artists', 'albums.artist_id', '=', $id)
            ->where('artist_id', $id)
            ->select('albums.*')
            ->orderBy('id', 'desc')->get();

        return view('artists.show')->with('artist', $artist)->with('albums', $albums);
    }
}
