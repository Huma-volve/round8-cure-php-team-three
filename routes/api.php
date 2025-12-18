<?php

use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PaymentWebhookController;
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

//! ==================== notifications ====================
Route::middleware('auth:sanctum')->group(function () {
    // Notification Routes
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
    Route::delete('/notifications/delete-all', [NotificationController::class, 'destroyAll']);

    Route::get('/notifications/{notification}', [NotificationController::class, 'show']);
    Route::post('/notifications/{notification}/mark-read', [NotificationController::class, 'markAsRead']);
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy']);
});

//! ==================== reviews ====================
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/my-reviews', [ReviewController::class, 'myReviews']);
    Route::get('/rateable-bookings', [ReviewController::class, 'rateableBookings']);
    Route::post('/reviews', [ReviewController::class, 'store']);
    
    Route::put('/reviews/{id}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);
});

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
Route::post('patient/bookings',[BookingController::class,'store']);
});

Route::post('/doctors', [DoctorController::class, 'store']);

Route::post('webhook/stripe', [PaymentWebhookController::class, 'handle']);

