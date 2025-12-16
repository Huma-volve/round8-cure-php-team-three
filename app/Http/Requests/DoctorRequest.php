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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:doctors,email',
            'password' => 'required|string|min:6',
            'specializations_id' => 'required|exists:specializations,id',
            'mobile_number' => 'required|string|max:15|unique:doctors,mobile_number',
            'license_number' => 'required|string|unique:doctors,license_number',
            'session_price' => 'required|numeric|min:0',
            'availability_slots' => 'required|array',
            'availability_slots.*.day' => 'required|string',
            'availability_slots.*.from' => 'required|date_format:H:i',
            'availability_slots.*.to' => 'required|date_format:H:i|after:availability_slots.*.from',
            'clinic_location' => 'required|array',
            'clinic_location.lat' => 'required|numeric|between:-90,90',
            'clinic_location.lng' => 'required|numeric|between:-180,180',
            'clinic_location.address' => 'required|string|max:255',
        ];
    }
}
