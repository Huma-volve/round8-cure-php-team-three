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
            'name' => ['required','string'],
            'email' => ['required','string','unique:doctors,email','email'],
            'password' => ['required','string','min:8'],
            'mobile_number' => ['required','string','max:20'],
            'license_number' => ['required','integer'],
            'session_price' => ['required','float'],
            'user_id' => ['required','integer','unique:doctors,user_id','exists:users,id'],
            'specializations_id' => ['required','exists:specializations,id'],
            'availability_slots' => ['nullable'],
            'clinic_location' => ['nullable'],
        ];
    }
}
