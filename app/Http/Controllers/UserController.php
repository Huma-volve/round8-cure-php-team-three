<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
   public function register(Request $request){

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|email|unique:users',
            'password' =>'required|min:8|confirmed',
            'mobile_number' =>'string|max:20'
        ]);

        $user= User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mobile_number' => $request->mobile_number,
            // 'profile_photo' => null,
        ]);
        $user->assignRole('patient');
        
        return response()->json([
        'message' =>'Sign Up Successfully' ,
        'user' =>$user->only(['id','name','email','mobile_number']) 
        ], 201);
    
   }


   public function login(Request $request){
        $request->validate([
        'email' => 'required|string|max:255',
        'password' =>'required|min:8',
    ]);
    if(!Auth::attempt($request->only('email','password'))){
        return response()->json(['message'=>'Invalid Email or Password'], 401);
    }else{
        $user=User::where('email',$request->email)->firstOrFail();
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

