<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['sometimes','integer','exists:users,id'],
            'specialization_id' => ['sometimes','exists:specializations,id'],
            'license_number' => ['sometimes'],
            'session_price' => ['sometimes'],
            'availability_slots' => ['nullable'],
            'clinic_location' => ['nullable'],
        ];
    }
}
