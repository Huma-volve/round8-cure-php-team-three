<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Http\Requests\DoctorProfileRequest;
use Illuminate\Support\Facades\Auth;

class DoctorProfileController extends Controller
{
    public function profileView()
    {
        $user = Auth::user();

        if (!$user || !$user->doctor) {

        return redirect()->route('login')->with('login_message','Please login as a doctor first.');
    }
       $doctor =  $user->doctor;

        return view ('doctors.profile.view',['doctor' => $doctor]);
    }
    public function editSlots()
    {
        $user = Auth::user();

        if (!$user || !$user->doctor) {

        return redirect()->route('login')->with('login_message','Please login as a doctor first.');
    }
       $doctor =  $user->doctor;

        return view ('doctors.profile.editSlots',['doctor' => $doctor]);
    }

    public function updateSlots(DoctorProfileRequest $request)
    {
          $user = Auth::user();
        
          $doctor = $user->doctor;

          $validation = $request->validated();

          $doctor->update($validation);

          return redirect()->route('profile.view')->with('success', 'Availability slots updated successfully');

    }

}
