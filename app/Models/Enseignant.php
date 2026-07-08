<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enseignant extends Model
{
    use HasFactory;

    protected $primaryKey = 'idEnseignant';
    protected $fillable = [
        'idPers',
        'idCours',
        'actif',
        'idAdmin',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function personne(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'idPers', 'idPers');
    }

    public function cours(): BelongsTo
    {
        return $this->belongsTo(Cours::class, 'idCours', 'idCours');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'idAdmin', 'ID');
    }
}
