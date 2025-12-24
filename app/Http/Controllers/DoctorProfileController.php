<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
}
