<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Frequence extends Model
{
    use HasFactory;

    protected $primaryKey = 'idFrequente';
    protected $fillable = [
        'idSalle',
        'idAcademi',
        'matricule',
        'commentaire',
        'idAdmin',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function salle(): BelongsTo
    {
        return $this->belongsTo(Salle::class, 'idSalle', 'idSalle');
    }

    public function anneeAcademique(): BelongsTo
    {
        return $this->belongsTo(AnneeAcademique::class, 'idAcademi', 'idAnnee');
    }

    public function eleve(): BelongsTo
    {
        return $this->belongsTo(Eleve::class, 'matricule', 'matricule');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'idAdmin', 'ID');
    }
}
