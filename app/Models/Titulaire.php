<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Titulaire extends Model
{
    use HasFactory;

    protected $primaryKey = 'idTitulaire';
    protected $fillable = [
        'idPers',
        'idSalle',
        'actif',
        'idAdmin',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function personne(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'idPers', 'idPers');
    }

    public function salle(): BelongsTo
    {
        return $this->belongsTo(Salle::class, 'idSalle', 'idSalle');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'idAdmin', 'ID');
    }
}
