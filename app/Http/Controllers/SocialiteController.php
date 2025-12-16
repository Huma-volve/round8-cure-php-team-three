<?php
namespace App\Http\Controllers;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
class SocialiteController extends Controller
{ 
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->stateless()
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try{
        // user from google
        $google_user = Socialite::driver('google')
            ->stateless()
            ->user();

        // search by social_id
        $user = User::where('social_id', $google_user->id)->first();

        // if not found, search by email
        if (!$user && $google_user->email) {
            $user = User::where('email', $google_user->email)->first();
        }

        // create user if not exists
        if (!$user) {
            $user = User::create([
                'name'        => $google_user->name,
                'email'       => $google_user->email,
                'social_id'   => $google_user->id,
                'social_type' => 'google',
                'password'    => Hash::make(uniqid()),
            ]);
        }

       return response()->json([
                'message' => 'Google login successful',
                'user'    => $user,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Google login failed',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}



