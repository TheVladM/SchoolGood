<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'name',
        'building',
        'floor',
        'capacity',
        'notes',
    ];

    public function classrooms(): HasMany
    {
        return $this->hasMany(Classroom::class);
    }

    public function displayLabel(): string
    {
        $parts = array_filter([$this->name, $this->building, $this->floor]);

        return implode(' — ', $parts);
    }
}
