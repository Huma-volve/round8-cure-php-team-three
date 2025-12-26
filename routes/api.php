<?php

use App\Http\Controllers\AdminController;
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

// notifications user 
Route::middleware('auth:sanctum')->group(function () {
    // Notification Routes
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
    Route::delete('/notifications/delete-all', [NotificationController::class, 'destroyAll']);
    Route::post('/notifications/test', [NotificationController::class, 'testCreate']);

    Route::get('/notifications/{notification}', [NotificationController::class, 'show']);
    Route::post('/notifications/{notification}/mark-read', [NotificationController::class, 'markAsRead']);
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy']);
});



// reviews 
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/my-reviews', [ReviewController::class, 'myReviews']);
    Route::get('/rateable-bookings', [ReviewController::class, 'rateableBookings']);
    Route::post('/reviews', [ReviewController::class, 'store']);

    Route::get('/reviews/top-doctors', [ReviewController::class, 'topRatedDoctors']);
    Route::get('/reviews/doctor/{doctorId}', [ReviewController::class, 'doctorReviews']);

    Route::put('/reviews/{id}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);
});

Route::get('/doctors/nearby', [DoctorController::class, 'nearby']); // Endpoint to find nearby doctors
Route::get('/doctors/{id}', [DoctorController::class, 'showById']); // Endpoint to get doctor details by ID
Route::get('/doctors', [DoctorController::class, 'allDoctors']); // Endpoint to list all doctors with filters
// ================== Auth system ============================
Route::post('register',[UserController::class,'register']);
Route::post('otpVerifyForRegister',[UserController::class,'otpVerifyForRegister']);
Route::post('login',[UserController::class,'login']);
Route::post('logout',[UserController::class,'logout'])
->middleware('auth:sanctum')
->name('logout');
;
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
Route::middleware(['auth:sanctum'])->group(function() {
Route::get('/patient/profile/show',[PatientProfileController::class ,'show']);
Route::put('/patient/profile/update',[PatientProfileController::class ,'update']);
Route::put('/patient/profile/changePassword', [PatientProfileController::class, 'changePassword']);
Route::post('patient/bookings',[BookingController::class,'store']);
Route::get('/patient/reviews/top-doctors', [ReviewController::class, 'topRatedDoctors']);
Route::get('/patient/reviews/doctor/{doctorId}', [ReviewController::class, 'doctorReviews']);
Route::get('patient/all-bookings',[BookingController::class,'getBookingsUser']);
Route::post('patient/bookings/{booking}/cancel',[BookingController::class,'cancelByPatient']);
Route::post('patient/bookings/{booking}/reschedule',[BookingController::class,'rescheduleByPatient']);
});

Route::get('payment-methods', [BookingController::class, 'getPaymentMethods']);


Route::post('webhook/stripe', [PaymentWebhookController::class, 'handle']);

Route::middleware(['auth:sanctum','role:doctor'])->group(function() {
    Route::get('doctor/bookings/stats', [BookingController::class, 'stats']);
    Route::get('doctor/bookings/today', [BookingController::class, 'today']);
    Route::get('doctor/bookings', [BookingController::class, 'index']);
    Route::patch('doctor/bookings/{booking}/status', [BookingController::class, 'updateStatus']);
    Route::patch('doctor/bookings/{booking}/reschedule', [BookingController::class, 'reschedule']);
});

Route::middleware(['auth:sanctum','role:admin'])->group(function () {


    Route::get('bookings/data', [AdminController::class,'dataBookings']);
    Route::delete('bookings/{booking}', [AdminController::class,'destroyBooking']);

    Route::get('payments/data', [AdminController::class,'dataPayments']);
    Route::delete('payments/{payment}', [AdminController::class,'destroyPayment']);

    Route::get('bookings/{booking}/payment', [AdminController::class,'getBookingPayment']);

});


