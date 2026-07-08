<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VilleNaissance extends Model
{
    use HasFactory;

    protected $primaryKey = 'idVille';
    protected $fillable = [
        'libelle',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function eleves(): HasMany
    {
        return $this->hasMany(Eleve::class, 'idVilleNaissance', 'idVille');
    }
}
