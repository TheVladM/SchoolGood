<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resident extends Model
{
    use HasFactory;

    protected $primaryKey = 'idResi';
    protected $fillable = [
        'idPers',
        'idQuartier',
        'description',
        'idAdmin',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function personne(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'idPers', 'idPers');
    }

    public function quartier(): BelongsTo
    {
        return $this->belongsTo(Quartier::class, 'idQuartier', 'idQuartier');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'idAdmin', 'ID');
    }
}
