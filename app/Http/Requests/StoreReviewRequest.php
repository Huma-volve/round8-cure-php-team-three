<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\BookingStatus;

class StoreReviewRequest extends FormRequest
{

    public function authorize(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return true;
    }


    public function rules(): array
    {
        $userId = auth()->id();

        return [
            'booking_id' => [
                'required',
                'integer',
                'exists:bookings,id',

                Rule::exists('bookings', 'id')->where(function ($query) use ($userId) {
                    $query->where('status', BookingStatus::Completed->value)
                          ->where('user_id', $userId);
                }),

                Rule::unique('reviews', 'booking_id')->where(function ($query) {
                    return $query->whereNotNull('booking_id');
                })
            ],

            'rating' => [
                'required',
                'integer',
                'min:1',
                'max:5'
            ],

            'comment' => [
                'nullable',
                'string',
                'max:1000'
            ]
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('booking_id')) {
            try {
                $booking = \App\Models\Booking::find($this->booking_id);

                if ($booking) {
                    $this->merge([
                        'doctor_id' => $booking->doctor_id,
                        'user_id' => $booking->user_id
                    ]);
                }
            } catch (\Exception $e) {
            }
        }
    }

    public function messages(): array
    {
        return [
            'booking_id.required' => 'رقم الحجز مطلوب',
            'booking_id.integer' => 'رقم الحجز يجب أن يكون رقماً صحيحاً',
            'booking_id.exists' => 'الحجز غير موجود أو غير مكتمل',
            'booking_id.unique' => 'تم إضافة تقييم لهذا الحجز مسبقاً',

            'rating.required' => 'التقييم مطلوب',
            'rating.integer' => 'التقييم يجب أن يكون رقماً صحيحاً',
            'rating.min' => 'التقييم يجب أن يكون على الأقل 1 نجمة',
            'rating.max' => 'التقييم يجب أن يكون على الأكثر 5 نجوم',

            'comment.string' => 'التعليق يجب أن يكون نصاً',
            'comment.max' => 'التعليق يجب ألا يتجاوز 1000 حرف'
        ];
    }


    public function attributes(): array
    {
        return [
            'booking_id' => 'رقم الحجز',
            'rating' => 'التقييم',
            'comment' => 'التعليق'
        ];
    }


    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->has('booking_id')) {
                $booking = \App\Models\Booking::find($this->booking_id);

                if (!$booking) {
                    $validator->errors()->add('booking_id', 'الحجز غير موجود');
                    return;
                }

                if ($booking->status !== BookingStatus::Completed->value) {
                    $validator->errors()->add('booking_id', 'لا يمكن تقييم حجز غير مكتمل');
                }

                if ($booking->user_id !== auth()->id()) {
                    $validator->errors()->add('booking_id', 'لا يمكنك تقييم حجز ليس لك');
                }

                $existingReview = \App\Models\Review::where('booking_id', $this->booking_id)->first();
                if ($existingReview) {
                    $validator->errors()->add('booking_id', 'تم تقييم هذا الحجز مسبقاً');
                }
            }
        });
    }
}
