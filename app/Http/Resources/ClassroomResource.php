<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassroomResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'level' => $this->level,
            'section' => $this->section?->value,
            'room' => $this->room,
            'location' => $this->location,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'main_teacher' => new UserResource($this->whenLoaded('mainTeacher')),
            'language_teacher' => new UserResource($this->whenLoaded('languageTeacher')),
        ];
    }
}
