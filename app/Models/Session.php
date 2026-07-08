<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Session extends Model
{
    use HasFactory;

    protected $table = 'exams';
    protected $primaryKey = 'idSession';
    protected $fillable = [
        'libelle',
        'description',
        'idTrimestre',
        'idPers',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function trimestre(): BelongsTo
    {
        return $this->belongsTo(Trimestre::class, 'idTrimestre', 'idTrimes');
    }

    public function personne(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'idPers', 'idPers');
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'idSession', 'idSession');
    }
}
