<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmploiDuTemps extends Model
{
    use HasFactory;

    protected $table = 'emploi_du_temps';
    protected $primaryKey = 'idTemps';
    protected $fillable = [
        'jour',
        'heure',
        'idClasse',
        'idCours',
        'idAdmin',
    ];

    protected $casts = [
        'heure' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class, 'idClasse', 'idClasse');
    }

    public function cours(): BelongsTo
    {
        return $this->belongsTo(Cours::class, 'idCours', 'idCours');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'idAdmin', 'ID');
    }
}
