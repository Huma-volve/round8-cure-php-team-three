<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Enums\BookingStatus;
use App\Http\Requests\BookingRequest;
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

    public function index()
    {
        $doctorId = auth()->user()->id();

        return Booking::with('user')
            ->where('doctor_id', $doctorId)
            ->orderBy('booking_date')
            ->get();
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        abort_if($booking->doctor_id !== auth()->user()->id(), 403);

        $booking->update([
            'status' => $request->status
        ]);

        return response()->json(['success' => true]);
    }

    public function reschedule(Request $request, Booking $booking)
    {
        abort_if($booking->doctor_id !== auth()->user()->id(), 403);

        $request->validate([
            'booking_date' => 'required|date|after:today',
            'booking_time' => 'required'
        ]);

        $booking->update([
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'status' => BookingStatus::Upcoming
        ]);

        return response()->json(['success' => true]);
    }
}
