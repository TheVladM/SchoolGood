<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paiement extends Model
{
    use HasFactory;

    protected $primaryKey = 'idPaie';
    protected $fillable = [
        'matricule',
        'idAca',
        'montant',
        'url',
        'comentaire',
        'idMode',
        'operation_ID',
        'idPers',
        'datePaie',
        'dateEnregistrer',
    ];

    protected $casts = [
        'montant' => 'float',
        'datePaie' => 'date',
        'dateEnregistrer' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function eleve(): BelongsTo
    {
        return $this->belongsTo(Eleve::class, 'matricule', 'matricule');
    }

    public function anneeAcademique(): BelongsTo
    {
        return $this->belongsTo(AnneeAcademique::class, 'idAca', 'idAnnee');
    }

    public function mode(): BelongsTo
    {
        return $this->belongsTo(Mode::class, 'idMode', 'idMode');
    }

    public function personne(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'idPers', 'idPers');
    }
}
