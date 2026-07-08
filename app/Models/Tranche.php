<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tranche extends Model
{
    use HasFactory;

    protected $primaryKey = 'idTranche';
    protected $fillable = [
        'libelle',
        'montant',
        'delai_mois',
        'delai_jour',
        'idScolarite',
        'actif',
        'idFondateur',
    ];

    protected $casts = [
        'montant' => 'float',
        'delai_mois' => 'integer',
        'delai_jour' => 'integer',
        'actif' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scolarite(): BelongsTo
    {
        return $this->belongsTo(Scolarite::class, 'idScolarite', 'idScolarite');
    }

    public function fondateur(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'idFondateur', 'idPers');
    }
}
