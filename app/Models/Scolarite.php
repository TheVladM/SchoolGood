<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Scolarite extends Model
{
    use HasFactory;

    protected $primaryKey = 'idScolarite';
    protected $fillable = [
        'inscription',
        'pension',
        'nbreTranche',
        'description',
        'idCycle',
        'idFondateur',
    ];

    protected $casts = [
        'inscription' => 'float',
        'pension' => 'float',
        'nbreTranche' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(Cycle::class, 'idCycle', 'idCycle');
    }

    public function fondateur(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'idFondateur', 'idPers');
    }

    public function tranches(): HasMany
    {
        return $this->hasMany(Tranche::class, 'idScolarite', 'idScolarite');
    }
}
