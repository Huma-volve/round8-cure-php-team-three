<?php

namespace App\Http\Controllers\Api\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OtpController extends Controller
{
    public function sendOtp(Request $request){
        $request->validate([
            'email'=> 'required|string|email'
        ]);
        $user = User::where('email',$request->email)->first();
        if(!$user){
            return response()->json([
                'message' =>'Email not found'
            ], 404);
        }
        $otp = rand(1000,9999);
        $user->update([
            'otp' =>$otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);
        return response()->json([
                'message' =>'Otp sent successfully',
                'otp'=>$otp
            ], 200);
    
    }
    public function otpVerify(Request $request){
         $request->validate([
            'email'=> 'required|string|email',
            'otp'=> 'required'
        ]);
        $user = User::where('email',$request->email)->first();
        if(!$user){
            return response()->json([
                'message' =>'User not found',
            ], 404); 
        }else if($user->otp !== $request->otp){
            return response()->json([
                'message' =>'Invalid Otp',
            ], 400); 
        }else if(Carbon::now()->greaterThan($user->otp_expires_at)){
            return response()->json([
                'message' =>'Expired Otp',
            ], 400); 
        }else{
            return response()->json([
                'message' =>'Otp verified successfully',
            ], 200); 
        }

    }
}
