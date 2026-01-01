<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialiteController extends Controller
{
    public function googleLogin(Request $request)
{
    $request->validate([
        'access_token' => 'required|string',
    ]);

    try {

        $googleUser = Socialite::driver('google')
            ->stateless()
            ->userFromToken($request->access_token);

        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {

            $user = User::create([

                 'name' => $googleUser->getName() 
              
                 ?? $googleUser->getNickname() 
              
                 ?? explode('@', $googleUser->getEmail())[0],
                
                 'email'    => $googleUser->getEmail(),

                'social_type' => 'google',
                
                'social_id'=> $googleUser->getId(),
                
                'password' => bcrypt(Str::random(16)),
            ]);

        } else {

            if (!$user->social_id) {

                $user->update([
                    'social_type'  => 'google',

                    'social_id' => $googleUser->getId(),
                ]);
            }
        }

        $token = $user->createToken('google-login')->plainTextToken;

        return response()->json([

            'status'  => true,

            'message' => 'Login Successfully',

            'data'    => [

                'user'  => $user,

                'token' => $token,
            ]
        ], 200);

    } catch (\Exception $e) {

        return response()->json([

            'status'  => false,

            'message' => 'Google login failed',

            'error'   => $e->getMessage(),

        ], 401);
    }
}

}
