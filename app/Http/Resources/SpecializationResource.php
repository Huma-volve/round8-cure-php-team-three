<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpecializationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $imageUrl = null;
        if ($this->image) {
            // إذا كانت الصورة URL كامل، استخدمها كما هي
            // وإلا افترض أنها ملف مخزن
            $imageUrl = filter_var($this->image, FILTER_VALIDATE_URL) 
                ? $this->image 
                : asset('storage/' . $this->image);
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $imageUrl,
        ];
    }
}

