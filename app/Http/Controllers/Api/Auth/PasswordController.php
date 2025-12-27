<?php

namespace App\Http\Controllers\Api\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    public function forget(Request $request)
    {
       
        $request->validate([
       
            'mobile_number' =>'required|string|max:20'
        ]);
       
        $user = User::where('mobile_number',$request->mobile_number)->first();
       
        if(!$user){
       
            return response()->json([
       
                'message' =>'Phone Number not found'
            ], 404);
        }
       
        return response()->json([
       
            'message' =>'Phone Number exist,you can reset your password now'
            ], 200);
    }

    public function reset(request $request)
    {
    
        $request->validate([
    
            'mobile_number'=> 'required|string',
    
            'password' =>'required|min:8|confirmed',
        ]);
    
        $user = User::where('mobile_number',$request->mobile_number)->first();
    
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
