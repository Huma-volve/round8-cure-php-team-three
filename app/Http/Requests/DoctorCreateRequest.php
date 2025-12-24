<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorCreateRequest extends FormRequest
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
            'user_id' => ['required','integer','unique:doctors,user_id','exists:users,id'],
            'specializations_id' => ['required','exists:specializations,id'],
            'license_number' => ['required','integer'],
            'session_price' => ['required'],
            'availability_slots' => ['nullable'],
            'clinic_location' => ['nullable'],
        ];
    }
}
