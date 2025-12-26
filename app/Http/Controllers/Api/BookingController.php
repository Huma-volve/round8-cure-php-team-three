<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Enums\BookingStatus;
use App\Http\Requests\BookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Booking;
use App\Models\Payment_method;
use App\Repositories\Bookings\BookingsRepositories;
use App\Repositories\Payments\PaymentRepositories;
use App\Services\Payments\PaymentService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Stripe\Refund;
use Stripe\Stripe;

class BookingController extends Controller
{
    public function __construct(
        protected  PaymentService $paymentService,
        protected  BookingsRepositories $bookingsRepositories,
        protected PaymentRepositories $paymentRepositories
        )
    {}
    public function store(BookingRequest $request)
    {
        $paymentMethod = Payment_method::findOrFail($request->payment_method_id);

        $data = $request->validated();
        $data['payment_method_id'] = $paymentMethod->id;
        $data['user_id'] = auth()->user()->id;
        $data['status'] = BookingStatus::Upcoming->value;

        $booking = Booking::create($data);
        $booking->load(['doctor', 'user']);

        (new NotificationService())->sendNewBookingNotification(
            $booking->doctor,
            $booking
        );

        $this->bookingsRepositories->deleteAppointment($booking);

        $payment = $this->paymentService->process($booking, $paymentMethod);

        return response()->json([
            'booking' => $booking->load('paymentMethod', 'payment', 'doctor', 'user'),
            'payment' => $payment,
            'message' => 'Payment is pending',
        ]);
    }



    public function cancelByPatient($id)
    {
        $booking = $this->bookingsRepositories->findById($id);

        $this->bookingsRepositories->update($booking, [
            'status' => BookingStatus::Cancelled->value,
        ]);

        $this->bookingsRepositories->restoreAppointment($booking);

        $payment = $booking->payment()->first();

        if ($payment && $payment->status === 'success' && $payment->paymentMethod->code === 'credit_card') {

            Stripe::setApiKey(config('services.stripe.secret'));

            $refund = Refund::create([
                'payment_intent' => $payment->transaction_id,
            ]);


            $this->paymentRepositories->update($payment, [
                'status' => 'refunded',
                'response' => $refund,
            ]);
        }

        return response()->json(['success' => true]);
    }



    public function getBookingsUser()
    {
        $bookings = $this->bookingsRepositories->getBookingsByUserId(auth()->user()->id);

        return response()->json($bookings);
    }

    public function index(Request $request)
    {
        $doctorId = auth()->user()->id;

        $query = Booking::with('user')
            ->where('doctor_id', $doctorId);

        $query = $this->bookingsRepositories->search($query, $request);

        return $query
            ->orderBy('booking_date')
            ->orderBy('booking_time')
            ->get();
    }


    public function updateStatus(Request $request, $id)
    {
        $booking = $this->bookingsRepositories->findById($id);

        abort_if($booking->doctor_id !== auth()->user()->id, 403);


        $this->bookingsRepositories->update($booking, [
            'status' => $request->status
        ]);

        return response()->json(['success' => true]);
    }

    public function rescheduleByPatient(UpdateBookingRequest $request, $id)
    {
        $booking = $this->bookingsRepositories->findById($id);

        $this->bookingsRepositories->restoreAppointment($booking);

        $this->bookingsRepositories->update($booking, [
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'status' => BookingStatus::Rescheduled->value,
        ]);

        $this->bookingsRepositories->deleteAppointment($booking);

        $paymentMethod = Payment_method::findOrFail($booking->payment_method_id);

        $payment = $this->paymentService->process($booking, $paymentMethod);

        return response()->json([
            'success' => true,
            'booking' => $booking,
            'payment' => $payment,
            'message' => 'Payment is pending',
        ]);
    }

    public function getPaymentMethods()
    {
        return Payment_method::all();
    }

}
