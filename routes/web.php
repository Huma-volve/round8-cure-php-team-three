<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HelperController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResourceDoctorController;
use App\Http\Controllers\DoctorProfileController;

Route::middleware('auth')->group(function () {
Route::get('/', function () {
    return view('welcome');
})->name('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth','role:doctor'])->prefix('doctor')->group(function () {
    Route::view('bookings', 'doctor.bookings.index')->name('doctor.bookings');
    Route::get('/profile/view',[DoctorProfileController::class,'profileView'])->name('profile.view');
    Route::get('/profile/editSlots',[DoctorProfileController::class,'editSlots'])->name('edit.slots');
    Route::put('/profile/updateSlots',[DoctorProfileController::class,'updateSlots'])->name('update.slots');
});

Route::middleware(['auth','role:admin'])->group(function () {
    Route::view('payments','admin.payments');
    Route::view('bookings','admin.booking');
    Route::resource('/doctors',ResourceDoctorController::class );
    Route::get('/helper/index',[HelperController::class ,'index'])->name('helper.index');
    Route::get('/helper/create',[HelperController::class ,'create'])->name('helper.create');
    Route::post('/helper/store',[HelperController::class ,'store'])->name('helper.store'); 
    Route::get('/home',[HomeController::class ,'index'])->name('home');
});



