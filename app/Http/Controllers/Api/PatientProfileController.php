<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PatientProfileController extends Controller
{
   public function show(){

    $user = auth()->user();

    $user->profile_photo_url = $user->profile_photo
    
    ? asset('storage/images/patient/' . $user->profile_photo)
    
    : null;

    $data = [
        
        'message' =>'Patient Profile fetched successfully',
        
        'user' =>$user,
        
        'patient' =>$user->patient,
    
    ];

    return response()->json($data, 200);
   }   

   public function update(Request $request){
   
    $user = auth()->user();

    $validation = Validator::make($request->all(),[
           
        'name' => 'sometimes|string|max:255',
        
        'email' => 'sometimes|email|unique:users,email,' . $user->id,
        
        'mobile_number' =>'string|max:20',
        
        'birth_date' =>'nullable|date',
        
        'location' =>'nullable',
        
        'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    if ($validation->fails()){
        
        return response()->json([
        
            'errors' => $validation->errors(),
        ],422);
    };

    $location = null;

        if ($request->has('location')) {

            if (is_string($request->location)) {
            
                $location = json_decode($request->location, true);
                
            } elseif (is_array($request->location)) {
               
                $location = $request->location;
            }
        }

        $user->update(

            $request->only([
            'name',
            'email',
            'mobile_number',
            'birth_date',  
            'location',  
        ])
        );

        if ($request->hasFile('profile_photo')) {
    
        $path = $request->file('profile_photo')->store('images/patients', 'public');
    
        $user->profile_photo = basename($path);

        $user->save();
    }

         $user->profile_photo_url = $user->profile_photo
          
         ? asset('storage/images/patients/' . $user->profile_photo)
         
         : null;
    
    $data = [
    
        'message' =>'Patient Profile Updated successfully',
    
        'user' =>$user,
    
        'patient' =>$user->patient,

    ];
    return response()->json($data, 200);
   } 

   public function changePassword(Request $request){

    $user = auth()->user();
   
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:8|confirmed',
    ]);
    if(!Hash::check($request->current_password,$user->password)){
        return response()->json([
            'message' => "Current password is incorrect",
        ], 400);
    }
    $user->update([
        'password' => Hash::make($request->new_password), 
    ]);
    return response()->json([
            'message' => "Password changed successfully",
        ], 200);
   }
}
