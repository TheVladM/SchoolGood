<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Salle extends Model
{
    use HasFactory;

    protected $primaryKey = 'idSalle';
    protected $fillable = [
        'libelle',
        'position',
        'surface',
        'idClasse',
        'actif',
        'idAdmin',
    ];

    protected $casts = [
        'surface' => 'float',
        'actif' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class, 'idClasse', 'idClasse');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'idAdmin', 'ID');
    }

    public function titulaires(): HasMany
    {
        return $this->hasMany(Titulaire::class, 'idSalle', 'idSalle');
    }

    public function frequentes(): HasMany
    {
        return $this->hasMany(Frequente::class, 'idSalle', 'idSalle');
    }

    public function emploiDuTemps(): HasMany
    {
        return $this->hasMany(EmploiDuTemps::class, 'idClasse', 'idClasse');
    }
}
