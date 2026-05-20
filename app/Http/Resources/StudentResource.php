<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'birth_date' => $this->birth_date?->toDateString(),
            'is_active' => $this->is_active,
            'left_at' => $this->left_at?->toDateTimeString(),
            'created_at' => $this->created_at?->toIso8601String(),
            'classroom' => new ClassroomResource($this->whenLoaded('classroom')),
            'parent' => new UserResource($this->whenLoaded('parent')),
        ];
    }
}
