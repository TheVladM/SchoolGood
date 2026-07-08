<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Epreuve extends Model
{
    use HasFactory;

    protected $primaryKey = 'idEpreuve';
    protected $fillable = [
        'libelle',
        'urlDoc',
        'auteur',
        'idNature',
        'idPers',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function nature(): BelongsTo
    {
        return $this->belongsTo(NatureEpreuve::class, 'idNature', 'idNature');
    }

    public function personne(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'idPers', 'idPers');
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'idEpreuve', 'idEpreuve');
    }
}
