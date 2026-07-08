<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParentEleve extends Model
{
    use HasFactory;

    protected $table = 'parents';
    protected $primaryKey = 'idParent';
    protected $fillable = [
        'idPers',
        'matricule',
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

    public function eleve(): BelongsTo
    {
        return $this->belongsTo(Eleve::class, 'matricule', 'matricule');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'idAdmin', 'ID');
    }
}
