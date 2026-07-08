<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quartier extends Model
{
    use HasFactory;

    protected $primaryKey = 'idQuartier';
    protected $fillable = [
        'libelle',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function residents(): HasMany
    {
        return $this->hasMany(Resident::class, 'idQuartier', 'idQuartier');
    }
}
