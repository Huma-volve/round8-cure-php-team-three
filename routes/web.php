<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ResourceDoctorController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/home',[HomeController::class ,'index'])->name('home');
Route::resource('/doctors',ResourceDoctorController::class );
