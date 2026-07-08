<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mode extends Model
{
    use HasFactory;

    protected $primaryKey = 'idMode';
    protected $fillable = [
        'libelle',
        'information',
        'actif',
        'idFondateur',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function fondateur(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'idFondateur', 'idPers');
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class, 'idMode', 'idMode');
    }
}
