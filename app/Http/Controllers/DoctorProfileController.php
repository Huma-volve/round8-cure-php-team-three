<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DoctorProfileRequest;
use Illuminate\Support\Facades\Auth;

class DoctorProfileController extends Controller
{
    public function profileView()
    {
        $user = Auth::user();

          if (!$user || !$user->hasRole('doctor') || !$user->doctor) {
        
            Auth::logout();
        
            return redirect()->route('login.form')->with('login_message', 'Please login as a doctor');
     }

       $doctor =  $user->doctor;

       return view ('doctors.profile.view',['doctor' => $doctor]);

    }
    public function editSlots()
    {
        $user = Auth::user();

        if (!$user || !$user->hasRole('doctor') || !$user->doctor) {
            
            Auth::logout();
            
            return redirect()->route('login.form')->with('login_message', 'Please login as a doctor');
        }
        
        $doctor =  $user->doctor;

       return view ('doctors.profile.edit',['doctor' => $doctor]);
    }
    public function updateSlots(DoctorProfileRequest $request)
    {
          $user = Auth::user();

            if (!$user || !$user->hasRole('doctor') || !$user->doctor) {
        
            Auth::logout();
        
            return redirect()->route('login.form')->with('login_message', 'Please login as a doctor');
        }
        
          $doctor = $user->doctor;

          $validation = $request->validated();

          $doctor->update($validation);

          return redirect()->route('profile.view')->with('success', 'Availability slots updated successfully');

    }

}
