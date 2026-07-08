<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluation extends Model
{
    use HasFactory;

    protected $primaryKey = 'idEval';
    protected $fillable = [
        'note',
        'appreciation',
        'matricule',
        'idEpreuve',
        'idCours',
        'idSession',
        'idPers',
    ];

    protected $casts = [
        'note' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function eleve(): BelongsTo
    {
        return $this->belongsTo(Eleve::class, 'matricule', 'matricule');
    }

    public function epreuve(): BelongsTo
    {
        return $this->belongsTo(Epreuve::class, 'idEpreuve', 'idEpreuve');
    }

    public function cours(): BelongsTo
    {
        return $this->belongsTo(Cours::class, 'idCours', 'idCours');
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class, 'idSession', 'idSession');
    }

    public function personne(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'idPers', 'idPers');
    }
}
