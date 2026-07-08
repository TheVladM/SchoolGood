<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trimestre extends Model
{
    use HasFactory;

    protected $primaryKey = 'idTrimes';
    protected $fillable = [
        'libelle',
        'periode',
        'idAca',
        'idAdmin',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function anneeAcademique(): BelongsTo
    {
        return $this->belongsTo(AnneeAcademique::class, 'idAca', 'idAnnee');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'idAdmin', 'ID');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class, 'idTrimestre', 'idTrimes');
    }
}
