<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Livre extends Model
{
    use HasFactory;

    protected $primaryKey = 'idLivre';
    protected $fillable = [
        'titre',
        'auteurs',
        'prix',
        'idSpecialite',
        'edition',
        'annee_parution',
        'idAdmin',
    ];

    protected $casts = [
        'prix' => 'float',
        'annee_parution' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function specialite(): BelongsTo
    {
        return $this->belongsTo(Specialite::class, 'idSpecialite', 'idSpecialite');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'idAdmin', 'ID');
    }

    public function cours(): HasMany
    {
        return $this->hasMany(Cours::class, 'idLivre', 'idLivre');
    }
}
