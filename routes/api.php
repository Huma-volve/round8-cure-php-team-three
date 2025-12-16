<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SocialiteController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register',[UserController::class,'register']);
Route::post('login',[UserController::class,'login']);
Route::post('logout',[UserController::class,'logout'])->middleware('auth:sanctum');
Route::delete('delete',[UserController::class,'deleteAccount'])->middleware('auth:sanctum');

//log in with google
Route::get('auth/google',[SocialiteController::class,'redirectToGoogle']);
Route::get('auth/google/callback',[SocialiteController::class,'handleGoogleCallback']);
