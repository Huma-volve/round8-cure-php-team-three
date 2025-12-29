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
     
        return $this->redirectWithNoCache
            ->with('login_message', 'Login First');
    }

    if ($user->hasRole('patient')) {
     
        return $this->redirectWithNoCache('welcome');
    }

    if ($user->hasRole('doctor')) {
     
        return $this->redirectWithNoCache
            ->with('doctor_message', 'Welcome Doctor');
    }

    if ($user->hasRole('admin') || $user->hasRole('helper') ) {
     
        return $this->redirectWithNoCache
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
        
        return $this->redirectWithNoCache('login.form');

       }
}
