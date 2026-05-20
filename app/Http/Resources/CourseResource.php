<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'day' => $this->day?->value,
            'created_at' => $this->created_at?->toIso8601String(),
            'classroom' => new ClassroomResource($this->whenLoaded('classroom')),
            'teacher' => new UserResource($this->whenLoaded('teacher')),
        ];
    }
}
