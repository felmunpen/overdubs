<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

use Illuminate\Support\Facades\DB;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    public function create_for_artists(): View
    {
        return view('auth.register_for_artists');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    // public function store(Request $request): RedirectResponse
    // {
    //     $request->validate([
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
    //         'password' => ['required', 'confirmed', Rules\Password::defaults()],
    //     ]);

    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //     ]);

    //     event(new Registered($user));

    //     Auth::login($user);

    //     return redirect(route('dashboard', absolute: false));
    // }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($request->year === "") {
            $request->year = 'NULL';
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'usertype' => $request->usertype,
            'year' => $request->year,
            'gender' => $request->gender,
        ]);


        event(new Registered($user));

        $user_id = DB::table('users')->where('name', $request->name)->first()->id;
        DB::table('users')->where('id', $user_id)->update([
            'country' => $request->country,
            'profile_pic' => $request->profile_pic
        ]);

        if ($request->usertype === 'Artist') {
            $id = DB::table('users')->where('name', $request->name)->first()->id;
            DB::table('messages')->insert([
                'sender_id' => $id,
                'receiver_id' => 1,
                'text' => $request->info
            ]);

            DB::table(table: 'artists')->insert([
                'name' => $request->artist_name,
                'registered' => 1,
                'artist_pic' => $request->profile_pic,
                'user_id' => $id
            ]);
        }

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
