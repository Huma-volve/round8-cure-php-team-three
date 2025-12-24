<?php

use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Dashboard\AdminNotificationController;
use App\Http\Controllers\Dashboard\DoctorNotificationController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\PaymentWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\UserController;
use App\Http\Controllers\Api\Auth\PasswordController;
use App\Http\Controllers\Api\Auth\OtpController;
use App\Http\Controllers\Api\Auth\SocialiteController;
use App\Http\Controllers\Api\PatientProfileController;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//! ==================== notifications user ====================
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

//! ==================== notifications Doctor ====================
Route::middleware(['auth:doctor'])->group(function () {
    Route::get('/doctor/notifications', [DoctorNotificationController::class, 'index']);
    Route::get('/doctor/notifications/unread-count', [DoctorNotificationController::class, 'unreadCount']);
    Route::post('/doctor/notifications/{id}/read', [DoctorNotificationController::class,'markAsRead']);
});

//! ==================== notifications Admin ====================
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/notifications', [AdminNotificationController::class, 'index']);
    Route::get('/admin/notifications/unread-count', [AdminNotificationController::class, 'unreadCount']);
    Route::post('/admin/notifications/{id}/read', [AdminNotificationController::class,'markAsRead']);
});

//! ==================== reviews ====================
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/my-reviews', [ReviewController::class, 'myReviews']);
    Route::get('/rateable-bookings', [ReviewController::class, 'rateableBookings']);
    Route::post('/reviews', [ReviewController::class, 'store']);

    Route::put('/reviews/{id}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);
});

Route::get('/doctors/nearby', [DoctorController::class, 'nearby']); // Endpoint to find nearby doctors

//! ================== Auth system ============================
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
Route::middleware(['auth:sanctum','role:patient'])->group(function() {
Route::get('/patient/profile/show',[PatientProfileController::class ,'show']);
Route::put('/patient/profile/update',[PatientProfileController::class ,'update']);
Route::put('/patient/profile/changePassword', [PatientProfileController::class, 'changePassword']);
Route::post('patient/bookings',[BookingController::class,'store']);
});


Route::post('webhook/stripe', [PaymentWebhookController::class, 'handle']);

