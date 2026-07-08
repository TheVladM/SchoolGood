<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $primaryKey = 'idMessages';
    protected $fillable = [
        'idExp_Pers',
        'idParent',
        'objet',
        'information',
        'type_message',
        'AnneeAcade',
        'valider',
        'idAdmin',
    ];

    protected $casts = [
        'valider' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function expéditeur(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'idExp_Pers', 'idPers');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ParentEleve::class, 'idParent', 'idParent');
    }

    public function anneeAcademique(): BelongsTo
    {
        return $this->belongsTo(AnneeAcademique::class, 'AnneeAcade', 'idAnnee');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'idAdmin', 'ID');
    }
}
