<?php

namespace App\Http\Requests;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;

class UpdateBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'doctor_id' => ['sometimes', 'integer', 'exists:doctors,id'],// for function withValidator
            'booking_date' => ['sometimes', 'date', 'after_or_equal:today'],
            'booking_time' => ['sometimes', 'date_format:H:i'],
        ];
    }

    // للتحقق من الوقت المتاح للطبيب
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $doctorId = $this->input('doctor_id');
            $bookingDate = $this->input('booking_date');
            $bookingTime = $this->input('booking_time');

            if (!$doctorId || !$bookingDate || !$bookingTime) {
                return;
            }
            //بحال كان في حجز بنفس الوقت والتاريخ من مستخدم تاني
            $conflictExists = Booking::query()
                ->where('doctor_id', $doctorId)
                ->whereDate('booking_date', $bookingDate)
                ->whereTime('booking_time', $bookingTime)
                ->where('status', '!=', BookingStatus::Cancelled->value)
                ->exists();

            if ($conflictExists) {
                $validator->errors()->add('booking_time', 'هذا الوقت محجوز مسبقاً لهذا الطبيب.');
                return;
            }

            $doctor = Doctor::find($doctorId);
            if (!$doctor) {
                return;
            }

            try {
                $date = Carbon::parse($bookingDate);
                $time = Carbon::createFromFormat('H:i', (string) $bookingTime);
            } catch (\Throwable) {
                return;
            }

            $dayName = strtolower($date->format('l'));
            $slots = is_array($doctor->availability_slots) ? $doctor->availability_slots : [];

            foreach ($slots as $slot) {
                if (!is_array($slot)) {
                    continue;
                }

                if (strtolower((string) ($slot['day'] ?? '')) !== $dayName) {
                    continue;
                }

                try {
                    $from = Carbon::createFromFormat('H:i', (string) ($slot['from'] ?? ''));
                    $to = Carbon::createFromFormat('H:i', (string) ($slot['to'] ?? ''));
                } catch (\Throwable) {
                    continue;
                }

                // within [from, to)
                if ($time->gte($from) && $time->lt($to)) {
                    return;
                }
            }

            $validator->errors()->add('booking_time', 'وقت الحجز يجب أن يكون ضمن مواعيد الطبيب المتوفرة في هذا اليوم.');
        });
    }
}
