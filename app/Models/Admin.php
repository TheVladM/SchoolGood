<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Admin extends Model
{
    use HasFactory;

    protected $primaryKey = 'ID';
    protected $fillable = [
        'nom',
        'username',
        'password',
        'photoURL',
        'actif',
        'typeAdmin',
        'mobile',
        'alanyaID',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function cycles(): HasMany
    {
        return $this->hasMany(Cycle::class, 'idAdmin', 'ID');
    }

    public function specialites(): HasMany
    {
        return $this->hasMany(Specialite::class, 'idAdmin', 'ID');
    }

    public function personnes(): HasMany
    {
        return $this->hasMany(Personne::class, 'idAdmin', 'ID');
    }

    public function salles(): HasMany
    {
        return $this->hasMany(Salle::class, 'idAdmin', 'ID');
    }

    public function anneeAcademiques(): HasMany
    {
        return $this->hasMany(AnneeAcademique::class, 'idAdmin', 'ID');
    }

    public function cours(): HasMany
    {
        return $this->hasMany(Cours::class, 'idAdmin', 'ID');
    }

    public function livres(): HasMany
    {
        return $this->hasMany(Livre::class, 'idAdmin', 'ID');
    }

    public function titulaires(): HasMany
    {
        return $this->hasMany(Titulaire::class, 'idAdmin', 'ID');
    }

    public function enseignants(): HasMany
    {
        return $this->hasMany(Enseignant::class, 'idAdmin', 'ID');
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class, 'idAdmin', 'ID');
    }

    public function rapports(): HasMany
    {
        return $this->hasMany(Rapport::class, 'idAdmin', 'ID');
    }

    public function residents(): HasMany
    {
        return $this->hasMany(Resident::class, 'idAdmin', 'ID');
    }

    public function modes(): HasMany
    {
        return $this->hasMany(Mode::class, 'idFondateur', 'ID');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'idAdmin', 'ID');
    }

    public function emploiDuTemps(): HasMany
    {
        return $this->hasMany(EmploiDuTemps::class, 'idAdmin', 'ID');
    }
}
