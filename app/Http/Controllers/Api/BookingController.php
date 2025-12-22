<?php

namespace App\Http\Controllers;

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


}
