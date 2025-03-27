<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    //
    public function send()
    {
        $user_id = $_POST['id'];
        $text = $_POST['message'];

        DB::table('messages')->insert(['user_id' => $user_id, 'text' => $text]);

        return redirect()->back();
    }

    public function send_message()
    {
        $receiver_id = $_POST['receiver_id'];
        $text = $_POST['message'];

        $receiver = DB::table('users')->where('id', '=', $receiver_id)->first();

        if ((Auth::user()->usertype !== 'Artist') && $receiver->usertype !== 'Artist') {
            DB::table('messages')->insert(['sender_id' => Auth::user()->id, 'receiver_id' => $receiver_id, 'text' => $text]);
        }

        return redirect()->back();
    }

    public function delete_message($message_id)
    {
        DB::table('messages')->where('id', '=', $message_id)->delete();

        return redirect()->back();
    }
}
