<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Repositories\Bookings\BookingsRepositories;
use App\Repositories\Payments\PaymentRepositories;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct(
        protected PaymentRepositories $paymentRepositories,
        protected BookingsRepositories $bookingsRepositories,
    )
    {}
    public function dataBookings(Request $request)
    {
        $bookings = Booking::with(['user','doctor','payment'])
            ->when($request->search, fn($q) =>
                $q->whereHas('user', fn($u) =>
                    $u->where('name','like','%'.$request->search.'%')
                )
            )
            ->orderByDesc('created_at')
            ->paginate(10);

        return response()->json([
            'data' => $bookings->items(),
            'links' => (string) $bookings->links('pagination::bootstrap-5')
        ]);
    }

    public function destroyBooking($id)
    {
        $booking = $this->bookingsRepositories->findById($id);

        $this->bookingsRepositories->delete($booking);

        return response()->json(['success'=>true]);
    }


    public function dataPayments(Request $request)
    {
        $q = $request->q;
        $status = $request->status;

        $payments = Payment::with(['booking.user','booking.doctor','paymentMethod'])
            ->when($q,function($query)use($q){
        $query->whereHas('booking.user',fn($u)=>$u->where('name','like',"%$q%"))
            ->orWhereHas('booking.doctor',fn($d)=>$d->where('name','like',"%$q%"));
            })
            ->when($status,fn($query)=>$query->where('status',$status))
            ->latest()
            ->get();

        return $payments;
    }

    public function destroyPayment($id)
    {
        $payment = $this->paymentRepositories->findById($id);

        $this->paymentRepositories->delete($payment);

        return response()->json(['success'=>true]);
    }

    public function getBookingPayment($id)
    {
        $booking = Booking::with(['user','doctor','payment.paymentMethod'])->findOrFail($id);

        return response()->json([
            'booking' => $booking,
            'payment' => $booking->payment
        ]);
    }


}
