<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cours extends Model
{
    use HasFactory;

    protected $primaryKey = 'idCours';
    protected $fillable = [
        'libelle',
        'note',
        'coefficient',
        'description',
        'idLivre',
        'actif',
        'idAdmin',
    ];

    protected $casts = [
        'note' => 'float',
        'coefficient' => 'float',
        'actif' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function livre(): BelongsTo
    {
        return $this->belongsTo(Livre::class, 'idLivre', 'idLivre');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'idAdmin', 'ID');
    }

    public function enseignants(): HasMany
    {
        return $this->hasMany(Enseignant::class, 'idCours', 'idCours');
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'idCours', 'idCours');
    }

    public function emploiDuTemps(): HasMany
    {
        return $this->hasMany(EmploiDuTemps::class, 'idCours', 'idCours');
    }
}
