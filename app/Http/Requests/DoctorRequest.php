<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['required','integer','exists:doctors,id'],
            'name' => ['required','string'],
            'email' => ['required','string','exists:doctors,email','email'],
            'password' => ['required','string','min:8'],
            'mobile_number' => ['required','string','max:20'],
            'license_number' => ['required','integer'],
            'session_price' => ['required','float'],
            'availability_slots' => ['null'],
            'clinic_location' => ['null'],
        ];
    }
}
