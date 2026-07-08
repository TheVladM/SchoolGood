<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Eleve extends Model
{
    use HasFactory;

    protected $primaryKey = 'matricule';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'matricule',
        'nom',
        'prenom',
        'dateNaissance',
        'lieuNaissance',
        'sexe',
        'langue',
        'photoURL',
        'actif',
        'idVilleNaissance',
        'idAdmin',
    ];

    protected $casts = [
        'dateNaissance' => 'date',
        'actif' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function villeNaissance(): BelongsTo
    {
        return $this->belongsTo(VilleNaissance::class, 'idVilleNaissance', 'idVille');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'idAdmin', 'ID');
    }

    public function frequentes(): HasMany
    {
        return $this->hasMany(Frequente::class, 'matricule', 'matricule');
    }

    public function salles(): BelongsToMany
    {
        return $this->belongsToMany(Salle::class, 'frequentes', 'matricule', 'idSalle')
            ->withPivot(['idFrequente', 'commentaire', 'idAcademi', 'idAdmin', 'created_at', 'updated_at']);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'matricule', 'matricule');
    }

    public function rapports(): HasMany
    {
        return $this->hasMany(Rapport::class, 'matricule', 'matricule');
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class, 'matricule', 'matricule');
    }

    public function parents(): HasMany
    {
        return $this->hasMany(ParentEleve::class, 'matricule', 'matricule');
    }
}
