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

              'name' => ['required','string','max:255','min:2'],

              'email' => ['required','string','max:255','email'],
              
              'password' => [
                        'required',
                        'min:8',
                        'regex:/[a-z]/',      
                        'regex:/[A-Z]/',      
                        'regex:/[0-9]/',      
                        'regex:/[@$!%*?&]/',  
                    ],
    
                'mobile_number' => ['required','string','regex:/^(\+2)?01[0-2,5][0-9]{8}$/'],

                'specializations_id' => ['required','exists:specializations,id'],

                
                'availability_slots' => ['nullable','array'],
                'availability_slots.*.day' => ['required','string'],
                'availability_slots.*.from' => ['required'],
                'availability_slots.*.to' => ['required'],

                'clinic_location' => ['nullable','array'],
                'clinic_location.city' => ['required','string'],
                'clinic_location.area' => ['required','string'],
                'clinic_location.address' => ['required','string'],
                
                ];
    }
}
