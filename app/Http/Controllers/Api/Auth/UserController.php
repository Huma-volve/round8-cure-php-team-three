<?php
namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Patient;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
   public function register(Request $request){

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|email|unique:users',
            'password' =>'required|min:8|confirmed',
            'mobile_number' =>'required|string|max:20|unique:users,mobile_number'
        ]);

        $user= User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mobile_number' => $request->mobile_number,
            'mobile_verified' => false,
        ]);
        $user->assignRole('patient');

         Patient::create([
        'user_id' => $user->id,
    ]);
        
        $otp = 1234;
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        
        Log::info("OTP for {$user->mobile_number} is $otp"); 

        return response()->json([
        'message' =>'OTP sent to your mobile.' ,
        'otp_code' => $otp
        ], 201);
    
   }

    public function otpVerifyForRegister(Request $request){
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
                'message' =>'Otp verified successfully , Sign Up Successfully',
                'user' =>$user->only(['id','name','email','mobile_number']),
            ], 200); 
        

    }
    public function login(Request $request){
        $request->validate([
        'mobile_number' =>'required|string|max:20',
        'password' =>'required|min:8',
    ]);
    if(!Auth::attempt($request->only('mobile_number','password'))){
        return response()->json(['message'=>'Invalid Phone Number or Password'], 401);
    }else{
        $user=User::where('mobile_number',$request->mobile_number)->firstOrFail();

        if (!$user->mobile_verified) {
            return response()->json([
                'message' => 'Mobile number not verified. Please verify OTP first.'
            ], 403);
        }

        $token = $user->createToken('user_token')->plainTextToken;

        return response()->json(
            [
                'message' =>'Login Successfully',
                'User' => $user,
                'Token' => $token,
    ], 201);}
       
}

   public function logout(Request $request){
    $request->user()->currentAccessToken()->delete();

    return response()->json(['message'=>'Logout Successfully'], 401);

   }

   public function deleteAccount(Request $request){
        $user=Auth::user();
        if(!$user){
            return response()->json([
                'message' =>'Account not authenticated',
            ], 401);
        }
        $user->delete();
        $request->user()->tokens()->delete();
        return response()->json([
            'message'=>'Account deleted successfully',
        ], 203);

   }

}

