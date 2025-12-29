<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); 
    }

    public function login(LoginRequest $request)
    {

    $validation = $request->validated();

   
    if (!Auth::attempt($validation)) {
        return redirect()
            ->route('login.form')
            ->with('login_message', 'Invalid email or password');
    }

    
    $request->session()->regenerate();

    $user = Auth::user();

    if(!$user){
          return redirect()
            ->route('login.form')
            ->with('login_message', 'Login First');
    }

    if ($user->hasRole('patient')) {
        return redirect()->route('welcome');
    }

    if ($user->hasRole('doctor')) {
        return redirect()
            ->route('profile.view')
            ->with('doctor_message', 'Welcome Doctor');
    }

    if ($user->hasRole('admin')) {
        return redirect()
            ->route('home')
            ->with('admin_message', 'Welcome Admin');
    }

  
    Auth::logout();

    return redirect()
        ->route('login.form')
        ->with('login_message', 'Your account role is invalid.');
}

    public function logout(Request $request)
    {
        Auth::logout();

         $request->session()->invalidate();

          $request->session()->regenerateToken();
        
        return redirect()->route('login.form');

       }
}
