<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cycle extends Model
{
    use HasFactory;

    protected $primaryKey = 'idCycle';
    protected $fillable = [
        'libelle',
        'description',
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

    public function classes(): HasMany
    {
        return $this->hasMany(Classe::class, 'idCycle', 'idCycle');
    }

    public function scolarites(): HasMany
    {
        return $this->hasMany(Scolarite::class, 'idCycle', 'idCycle');
    }
}
