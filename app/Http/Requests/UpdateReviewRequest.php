<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Enums\BookingStatus;

class UpdateReviewRequest extends FormRequest
{

    public function authorize(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        $reviewId = $this->route('id') ?? $this->route('review');

        if ($reviewId) {
            $review = \App\Models\Review::find($reviewId);

            if ($review && $review->user_id === auth()->id()) {
                return true;
            }
        }

        return false;
    }


    public function rules(): array
    {
        return [
            'rating' => [
                'sometimes',
                'required',
                'integer',
                'min:1',
                'max:5'
            ],

            'comment' => [
                'sometimes',
                'nullable',
                'string',
                'max:1000'
            ]
        ];
    }

    /**
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('comment') && empty(trim($this->comment))) {
            $this->merge([
                'comment' => null
            ]);
        }

        if ($this->has('rating') && is_string($this->rating)) {
            $this->merge([
                'rating' => (int) $this->rating
            ]);
        }
    }

    /**
     */
    public function messages(): array
    {
        return [
            // رسائل rating
            'rating.required' => 'التقييم مطلوب إذا قمت بإرساله',
            'rating.integer' => 'التقييم يجب أن يكون رقماً صحيحاً',
            'rating.min' => 'التقييم يجب أن يكون على الأقل 1 نجمة',
            'rating.max' => 'التقييم يجب أن يكون على الأكثر 5 نجوم',

            // رسائل comment
            'comment.string' => 'التعليق يجب أن يكون نصاً',
            'comment.max' => 'التعليق يجب ألا يتجاوز 1000 حرف'
        ];
    }

    /**
     */
    public function attributes(): array
    {
        return [
            'rating' => 'التقييم',
            'comment' => 'التعليق'
        ];
    }

    /**
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $reviewId = $this->route('id') ?? $this->route('review');

            if ($reviewId) {
                $review = \App\Models\Review::find($reviewId);

                if (!$review) {
                    $validator->errors()->add('review', 'التقييم غير موجود');
                    return;
                }

                if ($review->user_id !== auth()->id()) {
                    $validator->errors()->add('review', 'غير مصرح لك بتحديث هذا التقييم');
                }

                if ($review->booking && $review->booking->status !== BookingStatus::Completed->value) {
                    $validator->errors()->add('booking', 'لا يمكن تعديل تقييم لحجز غير مكتمل');
                }
            }
        });
    }

    /**

     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        if ($reviewId = $this->route('id')) {
            $review = \App\Models\Review::find($reviewId);

            if ($review) {
                if (!isset($validated['rating'])) {
                    $validated['rating'] = $review->rating;
                }

                if (!isset($validated['comment'])) {
                    $validated['comment'] = $review->comment;
                }
            }
        }

        return $validated;
    }
}
