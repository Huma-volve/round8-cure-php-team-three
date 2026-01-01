<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FavouriteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return [
        //     'id' => $this->id,
        //     'user_id' => $this->user_id,
        //     'doctor_id' => $this->doctor_id,
        //     'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        //     'created_at_human' => $this->created_at->diffForHumans(),
        //     'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        //     'doctor' => new DoctorResource($this->whenLoaded('doctor')),
        // ];
        return [
            // بيانات المفضلة
            'id' => $this->id,
            'user_id' => $this->user_id,
            'doctor_id' => $this->doctor_id,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'created_at_human' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),

            // بيانات الطبيب مباشرة
            'doctor' => [
                'id' => $this->doctor->id,
                'user_id' => $this->doctor->user_id,
                'license_number' => $this->doctor->license_number,
                'session_price' => $this->doctor->session_price,
                'availability_slots' => $this->doctor->availability_slots,
                'clinic_location' => $this->doctor->clinic_location,
                'about_me' => $this->doctor->about_me,
                'experience_years' => $this->doctor->experience_years,

                // بيانات التخصص مباشرة (بدون الحاجة للدخول في specialization.specialization)
                'specialization_id' => $this->doctor->specialization->id ?? null,
                'specialization_name' => $this->doctor->specialization->name ?? null,
                'specialization_description' => $this->doctor->specialization->description ?? null,


                'name' => $this->doctor->user->name,
                'email' => $this->doctor->user->email,
                'mobile_number' => $this->doctor->user->mobile_number,
                'profile_photo' => $this->doctor->user->profile_photo ?? null,

                'average_rating' => $this->doctor->reviews->avg('rating'),
                'reviews_count' => $this->doctor->reviews->count(),
            ]
        ];
    }
}

