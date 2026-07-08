<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnneeAcademique extends Model
{
    use HasFactory;

    protected $primaryKey = 'idAnnee';
    protected $fillable = [
        'libelle',
        'periode',
        'idAdmin',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'idAdmin', 'ID');
    }

    public function trimestres(): HasMany
    {
        return $this->hasMany(Trimestre::class, 'idAca', 'idAnnee');
    }

    public function frequentes(): HasMany
    {
        return $this->hasMany(Frequente::class, 'idAcademi', 'idAnnee');
    }

    public function rapports(): HasMany
    {
        return $this->hasMany(Rapport::class, 'idAca', 'idAnnee');
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class, 'idAca', 'idAnnee');
    }
}
