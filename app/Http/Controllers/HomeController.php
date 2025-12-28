<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class HomeController extends Controller
{
    public function index(){
        $doctors = User::role('doctor')->limit('5')->get();
        return view ('home' , ['doctors' => $doctors]);
    }
}
