<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResourceDoctorController;
use App\Http\Controllers\DoctorProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth','role:doctor'])->group(function(){
Route::get('/profile/view',[DoctorProfileController::class,'profileView'])->name('profile.view');
Route::get('/profile/editSlots',[DoctorProfileController::class,'editSlots'])->name('edit.slots');
Route::put('/profile/updateSlots',[DoctorProfileController::class,'updateSlots'])->name('update.slots');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login',[AuthController::class ,'login'])->name('login');
Route::post('/logout',[AuthController::class ,'logout'])->name('logout');

Route::get('/home',[HomeController::class ,'index'])->name('home');

Route::resource('/doctors',ResourceDoctorController::class );

Route::middleware(['auth','role:doctor'])->prefix('doctor')->group(function () {
        Route::view('bookings', 'doctor.bookings.index')->name('doctor.bookings');
    });


Route::middleware(['auth','role:admin'])->group(function () {
    Route::view('payments','admin.payments');
    Route::view('bookings','admin.booking');

});



