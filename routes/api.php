<?php

use App\Http\Controllers\DoctorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\PatientProfileController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('register',[UserController::class,'register']);
Route::post('login',[UserController::class,'login']);
Route::post('logout',[UserController::class,'logout'])->middleware('auth:sanctum');
Route::delete('delete',[UserController::class,'deleteAccount'])->middleware('auth:sanctum');

//password => forget & reset
Route::post('forget',[PasswordController::class,'forget']);
Route::post('reset',[PasswordController::class,'reset']);

//otp => send & verify
Route::post('sendOtp',[OtpController::class,'sendOtp']);
Route::post('otpVerify',[OtpController::class,'otpVerify']);

//log in with google
Route::get('auth/google',[SocialiteController::class,'redirectToGoogle']);
Route::get('auth/google/callback',[SocialiteController::class,'handleGoogleCallback']);

//patient profile
Route::middleware(['auth:sanctum', 'role:patient'])->group(function() {
Route::get('/patient/profile/show',[PatientProfileController::class ,'show']);
Route::put('/patient/profile/update',[PatientProfileController::class ,'update']);
Route::put('/patient/profile/changePassword', [PatientProfileController::class, 'changePassword']);
});

Route::post('/doctors', [DoctorController::class, 'store']);

