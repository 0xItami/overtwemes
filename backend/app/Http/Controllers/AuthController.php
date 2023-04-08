<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
    }

    public function redirectToTwitter()
    {
        return Socialite::driver('twitter')->redirect();
    }

    public function handleTwitterCallback()
    {
        try {
            $user = Socialite::driver('twitter')->user();
            $existingUser = User::where('email', $user->getEmail())->first();

            if ($existingUser) {
                Auth::login($existingUser, true);
                $token = $existingUser->createToken('access_token')->plainTextToken;
                return response()->json(['access_token' => $token], 200);
            } else {
                $newUser = new User;
                $newUser->name = $user->getName();
                $newUser->email = $user->getEmail();
                $newUser->password = bcrypt('password'); // Set a default password
                $newUser->save();

                // Authenticate the user and generate an access token
                Auth::login($newUser, true);
                $token = $newUser->createToken('access_token')->plainTextToken;
                return response()->json(['access_token' => $token], 200);
            }
        } catch (\Exception $e) {
            if ($e instanceof \Laravel\Socialite\One\MissingTemporaryCredentialsException) {
                //return a 401 Unauthorized response with an error message
                return response()->json(['error' => 'Unable to authenticate with Twitter. Please try again.'], 401);
            }
            //log the error and return a 500 Internal Server Error response with a generic error message
            Log::error($e->getMessage());
            return response()->json(['error' => 'Something went wrong. Please try again later.'], 500);
        }
    }

    public function testApi()
    {
        return json_encode([
            "status" => "working",
            "message" => "hello world",
            "code" => "200",
        ]);
    }
}
