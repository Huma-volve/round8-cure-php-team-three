<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'license_number' => $this->license_number,
            'session_price' => $this->session_price,
            'availability_slots' => $this->availability_slots,
            'clinic_location' => $this->clinic_location,
            'about_me' => $this->about_me,
            'experience_years' => $this->experience_years,
            'specialization' => new SpecializationResource($this->whenLoaded('specialization')),
            'user' => $this->when($this->relationLoaded('user') && $this->user, [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'mobile_number' => $this->user->mobile_number,
                'profile_photo' => $this->user->profile_photo 
                    ? asset('storage/images/patients/' . $this->user->profile_photo) 
                    : null,
            ]),
            'average_rating' => $this->when($this->relationLoaded('reviews'), function () {
                return round($this->averageRating(), 2);
            }),
            'reviews_count' => $this->when($this->relationLoaded('reviews'), function () {
                return $this->reviewsCount();
            }),
        ];
    }
}

