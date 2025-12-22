<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    public function forget(Request $request){
        $request->validate([
            'email'=> 'required|string|email'
        ]);
        $user = User::where('email',$request->email)->first();
        if(!$user){
            return response()->json([
                'message' =>'Email not found'
            ], 404);
        }
        return response()->json([
                'message' =>'Email exist,you can reset your password now'
            ], 200);
    }
    public function reset(request $request){
     $request->validate([
        'email'=> 'required|string|email',
        'password' =>'required|min:8|confirmed',
        ]);
        $user = User::where('email',$request->email)->first();
        if(!$user){
            return response()->json([
                'message' =>'User not found'
            ], 404);   
        }
        $user->update([
            'password' => Hash::make($request->password),
            'otp' => null,
            'otp_expires_at' => null
        ]);
         return response()->json([
                'message' =>'Password reset successfully'
            ], 200);   
    }
}
