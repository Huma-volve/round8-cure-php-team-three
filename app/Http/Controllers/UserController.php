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
            'password' =>'required|min:8|confirmed'
        ]);

    $user= User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // 'profile_photo' => null,
        ]);
        return response()->json([
        'message' =>'Sign Up Successfully' ,
        'user' =>$user->only(['id','name','email']) 
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
}
