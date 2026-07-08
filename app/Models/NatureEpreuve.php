<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NatureEpreuve extends Model
{
    use HasFactory;

    protected $primaryKey = 'idNature';
    protected $fillable = [
        'libelle',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function epreuves(): HasMany
    {
        return $this->hasMany(Epreuve::class, 'idNature', 'idNature');
    }
}
