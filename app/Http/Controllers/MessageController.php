<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MessageController extends Controller
{
    /**
     * Sends a message to another user or admin.
     * 
     * @param \Illuminate\Http\Request $request Contains the text message and the identifier of the user who is going to receive it.
     */
    public function send_message(Request $request): RedirectResponse
    {
        $receiver_id = $request->post('receiver_id');
        $text = $request->post('message');

        $receiver = DB::table('users')->where('id', '=', $receiver_id)->first();

        if ((Auth::user()->usertype !== 'Artist') && $receiver->usertype !== 'Artist') {
            DB::table('messages')->insert(['sender_id' => Auth::user()->id, 'receiver_id' => $receiver_id, 'text' => $text]);
        }

        return redirect()->back();
    }

    /**
     * Sends a report or suggestion to the admins.
     * 
     * @param \Illuminate\Http\Request $request Contains the text report.
     * 
     */
    public function send_report(Request $request): RedirectResponse
    {
        $text = $request->post('message');

        $receiver = DB::table('users')->where('usertype', '=', 'Admin')->first();

        if ((Auth::user()->usertype !== 'Artist') && $receiver->usertype !== 'Artist') {
            DB::table('messages')->insert(['sender_id' => Auth::user()->id, 'receiver_id' => $receiver->id, 'text' => $text]);
        }

        return redirect()->back();
    }

    /**
     * Deletes a message.
     * 
     * @param int $message_id The message identifier.
     */
    public function delete_message($message_id): RedirectResponse
    {
        DB::table('messages')->where('id', '=', $message_id)->delete();

        return redirect()->back();
    }
}
