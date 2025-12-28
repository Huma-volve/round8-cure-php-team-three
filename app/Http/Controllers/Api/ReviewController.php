<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Review;
use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Doctor;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    protected $notificationService;

    public function __construct()
    {
        $this->notificationService = new NotificationService();
    }

    /**
     * 1. عرض جميع التقييمات
     */
    public function index(): JsonResponse
    {
        try {
            $reviews = Review::with([
                'user:id,name,profile_photo',
                'doctor:id,name',
                'booking:id,booking_date,booking_time'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

            return response()->json([
                'success' => true,
                'message' => 'تم جلب التقييمات بنجاح',
                'data' => $reviews
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب التقييمات',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * 2. إنشاء تقييم جديد (باستخدام StoreReviewRequest)
     */
    public function store(StoreReviewRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            // البيانات تم التحقق منها في Form Request
            $validatedData = $request->validated();

            // الحصول على الحجز (موجود ومكتمل - تم التحقق في Form Request)
            $booking = Booking::findOrFail($validatedData['booking_id']);

            // إنشاء التقييم
            $review = Review::create([
                'booking_id' => $validatedData['booking_id'],
                'user_id' => Auth::id(),
                'doctor_id' => $booking->doctor_id,
                'rating' => $validatedData['rating'],
                'comment' => $validatedData['comment'] ?? null
            ]);

            // إرسال إشعار للطبيب
            try {
    // إرسال إشعار للطبيب
    $this->notificationService->sendNewReviewNotification(
        $booking->doctor,
        [
            'rating' => $review->rating,
            'patient_name' => Auth::user()->name
        ]
    );
} catch (\Exception $e) {
    \Log::error('Failed to send notification: ' . $e->getMessage());
    // لا توقف العملية إذا فشل الإشعار
}

            DB::commit();

            // تحميل العلاقات
            $review->load(['user:id,name,profile_photo', 'doctor:id,name']);

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة التقييم بنجاح',
                'data' => $review
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة التقييم',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * 3. عرض تقييم معين
     */
    public function show($id): JsonResponse
    {
        try {
            $review = Review::with([
                'user:id,name,profile_photo,email,mobile_number',
                'doctor:id,name,specializations_id',
                'doctor.specialization:id,name',
                'booking:id,booking_date,booking_time,price'
            ])->find($id);

            if (!$review) {
                return response()->json([
                    'success' => false,
                    'message' => 'التقييم غير موجود'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'تم جلب التقييم بنجاح',
                'data' => $review
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب التقييم',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * 4. تحديث التقييم (باستخدام UpdateReviewRequest)
     */
    public function update(UpdateReviewRequest $request, $id): JsonResponse
    {
        DB::beginTransaction();

        try {
            $review = Review::find($id);

            if (!$review) {
                return response()->json([
                    'success' => false,
                    'message' => 'التقييم غير موجود'
                ], 404);
            }

            // التحقق من أن المستخدم هو صاحب التقييم
            if ($review->user_id != Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'غير مصرح لك بتحديث هذا التقييم'
                ], 403);
            }

            // الحصول على البيانات المفحوصة
            $validatedData = $request->validated();

            // تحديث التقييم
            $review->update($validatedData);

            DB::commit();

            // تحميل العلاقات
            $review->refresh()->load(['user:id,name,profile_photo', 'doctor:id,name']);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث التقييم بنجاح',
                'data' => $review
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث التقييم',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * 5. حذف التقييم
     */
    public function destroy($id): JsonResponse
    {
        DB::beginTransaction();

        try {
            $review = Review::find($id);

            if (!$review) {
                return response()->json([
                    'success' => false,
                    'message' => 'التقييم غير موجود'
                ], 404);
            }

            // التحقق من أن المستخدم هو صاحب التقييم
            if ($review->user_id != Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'غير مصرح لك بحذف هذا التقييم'
                ], 403);
            }

            // حذف التقييم
            $review->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف التقييم بنجاح'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف التقييم',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * 6. الحصول على تقييمات طبيب معين
     */
    public function doctorReviews($doctorId): JsonResponse
    {
        try {
            $doctor = Doctor::find($doctorId);

            if (!$doctor) {
                return response()->json([
                    'success' => false,
                    'message' => 'الطبيب غير موجود'
                ], 404);
            }

            $reviews = Review::with(['user:id,name,profile_photo', 'booking:id,booking_date'])
                ->where('doctor_id', $doctorId)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            // حساب الإحصائيات
            $averageRating = Review::where('doctor_id', $doctorId)->avg('rating');
            $totalReviews = Review::where('doctor_id', $doctorId)->count();

            return response()->json([
                'success' => true,
                'message' => 'تم جلب تقييمات الطبيب بنجاح',
                'data' => [
                    'doctor' => [
                        'id' => $doctor->id,
                        'name' => $doctor->name,
                        'specialization' => $doctor->specialization->name ?? null,
                        'average_rating' => round($averageRating, 1),
                        'total_reviews' => $totalReviews
                    ],
                    'reviews' => $reviews
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب تقييمات الطبيب',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * 7. الحصول على تقييمات المستخدم الحالي
     */
    public function myReviews(): JsonResponse
    {
        try {
            $reviews = Review::with(['doctor:id,name', 'booking:id,booking_date'])
                ->where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'message' => 'تم جلب تقييماتك بنجاح',
                'data' => $reviews
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب التقييمات',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * 8. الحصول على الحجوزات المكتملة القابلة للتقييم
     */
    public function rateableBookings(): JsonResponse
    {
        try {
            $bookings = Booking::with(['doctor:id,name,specializations_id', 'doctor.specialization:id,name'])
                ->where('user_id', Auth::id())
                ->where('status', BookingStatus::Completed->value)
                ->whereDoesntHave('review')
                ->orderBy('booking_date', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'message' => 'تم جلب الحجوزات القابلة للتقييم',
                'data' => $bookings
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب الحجوزات',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function topRatedDoctors(): JsonResponse
    {
        try {
            $rows = Doctor::leftJoin('reviews', 'reviews.doctor_id', '=', 'doctors.id')
                ->leftJoin('specializations', 'specializations.id', '=', 'doctors.specializations_id')
                ->join('users', 'users.id', '=', 'doctors.user_id') // Join with users table
                ->select(
                    'doctors.id',
                    'users.name', // Get name from users table
                    'users.email', // Optionally get other user details
                    'users.profile_photo',
                    'doctors.clinic_location',
                    'doctors.session_price',
                    'doctors.availability_slots',
                    DB::raw('COALESCE(AVG(reviews.rating), 0) as average_rating'),
                    DB::raw('COUNT(reviews.id) as reviews_count'),
                    DB::raw('COALESCE(specializations.name, NULL) as specialization')
                )
                ->groupBy('doctors.id', 'users.name', 'users.email', 'users.profile_photo', 'specializations.name', 'doctors.clinic_location', 'doctors.session_price', 'doctors.availability_slots')
                ->orderByDesc(DB::raw('COALESCE(AVG(reviews.rating), 0)'))
                ->orderByDesc(DB::raw('COUNT(reviews.id)'))
                ->limit(8)
                ->get();

            $result = $rows->map(function ($row) {
                return [
                    'id' => (int) $row->id,
                    'name' => $row->name,
                    'profile_photo' => $row->profile_photo,
                    'specialization' => $row->specialization,
                    'clinic_location' => is_string($row->clinic_location) ? json_decode($row->clinic_location, true) : $row->clinic_location,
                    'session_price' => (float) $row->session_price,
                    'availability_slots' => is_string($row->availability_slots) ? json_decode($row->availability_slots, true) : $row->availability_slots,
                    'average_rating' => round((float) $row->average_rating, 1),
                    'reviews_count' => (int) $row->reviews_count,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'تم جلب أعلى 8 أطباء حسب التقييم بنجاح',
                'data' => $result,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب أعلى الأطباء بالتقييم',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
