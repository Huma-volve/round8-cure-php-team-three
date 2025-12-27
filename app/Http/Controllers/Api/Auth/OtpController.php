<?php

namespace App\Http\Controllers\Api\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OtpController extends Controller
{
    public function sendOtp(Request $request)
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
     
        $otp = 1234;
     
        $user->update([
     
            'otp' =>$otp,
     
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);
     
        return response()->json([
     
            'message' =>'Otp sent successfully',
     
            'otp'=>$otp
            ], 200);
    
    }

    public function otpVerify(Request $request)
    {
    
        $request->validate([
    
            'mobile_number' =>'required|string|max:20',
    
            'otp'=> 'required'
        ]);
        
        $user = User::where('mobile_number',$request->mobile_number)->first();
        
        if(!$user){
    
            return response()->json([
    
                'message' =>'User not found',
            ], 404); 
        }
        
        if($user->otp !== $request->otp){
    
            return response()->json([
    
                'message' =>'Invalid Otp',
            ], 400); 
        } 

        if(Carbon::now()->greaterThan($user->otp_expires_at)){
    
            return response()->json([
    
                'message' =>'Expired Otp',
            ], 400); 
        }
        
        $user->update([

            'mobile_verified' => true,
            
            'otp' => null,
            
            'otp_expires_at' => null,
        ]);

            return response()->json([

                'message' =>'Otp verified successfully',
            ], 200); 
        

    }
}
