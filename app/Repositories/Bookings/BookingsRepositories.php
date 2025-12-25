<?php

namespace App\Repositories\Bookings;

use App\Models\Booking;

class BookingsRepositories
{
    public function create(array $data): Booking
    {
        return Booking::create($data);
    }

    public function update(Booking $booking, array $data): Booking
    {
        $booking->update($data);
        return $booking;
    }
    public function findById($id): ?Booking
    {
        return Booking::findOrFail($id);
    }

    public function delete(Booking $booking): void
    {
        $booking->delete();
    }
    public function getBookingsByUserId($userId)
    {
        return Booking::with(['doctor', 'payment', 'paymentMethod'])
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get();
    }

    public function search($query, $request)
    {
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
        return $query;
    }
}
