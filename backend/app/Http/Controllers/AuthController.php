<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function redirectToTwitter()
    {
        return Socialite::driver('twitter')->redirect();
    }

    public function handleTwitterCallback()
    {
        $user = Socialite::driver('twitter')->user();

        $existingUser = User::where('email', $user->getEmail())->first();

        if($existingUser) {
            Auth::login($existingUser, true);
            $token = $existingUser->createToken('authToken')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            $newUser = new User;
            $newUser->name = $user->getName();
            $newUser->email = $user->getEmail();
            $newUser->password = bcrypt('password'); // Set a default password
            $newUser->save();

            Auth::login($newUser, true);
            $token = $newUser->createToken('authToken')->accessToken;
            return response()->json(['token' => $token], 200);
        }
    }

    public function testApi(){
        return json_encode([
            "status"=>"working",
            "message"=>"delight fucking built this shit",
            "code"=>"200",
        ]);
    }
}