<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Enums\BookingStatus;
use App\Http\Requests\BookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Booking;
use App\Models\Payment_method;
use App\Services\Payments\PaymentService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(protected  PaymentService $paymentService)
    {}
    public function store(BookingRequest $request)
    {
        //transaction here =================================
        $paymentMethod = Payment_method::findOrFail($request->payment_method_id);

        $data = $request->validated();
        $data['payment_method_id'] = $paymentMethod->id;
        $data['user_id'] = auth()->user()->id;
        $data['status'] = BookingStatus::Upcoming;

        $booking = Booking::create($data);

        $payment = $this->paymentService->process($booking, $paymentMethod);

        return response()->json([
            'booking' => $booking,
            'payment' => $payment,
            'message' => 'Payment is pending',
        ]);

    }

    public function index(Request $request)
    {
        $doctorId = 1; // مؤقتاً لحين اكتمال auth

        $query = Booking::with('user')
            ->where('doctor_id', $doctorId);

        if ($request->filled('q')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%');
            });
        }

        if ($request->filled('from')) {
            $query->whereDate('booking_date', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('booking_date', '<=', $request->to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }


        return $query
            ->orderBy('booking_date')
            ->orderBy('booking_time')
            ->get();
    }


    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        abort_if($booking->doctor_id !== auth()->user()->id, 403);


        $booking->update([
            'status' => $request->status
        ]);

        return response()->json(['success' => true]);
    }

    public function rescheduleByPatient(UpdateBookingRequest $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $booking->update([
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'status' => BookingStatus::Rescheduled->value,
        ]);

        return response()->json([
            'success' => true,
            'booking' => $booking,
        ]);
    }

}
