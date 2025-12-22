<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
class HomeController extends Controller
{
    public function index(){
        $doctors = Doctor::all();
        return view ('home' , ['doctors' => $doctors]);
    }
}
