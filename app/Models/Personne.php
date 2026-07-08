<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Personne extends Model
{
    use HasFactory;

    protected $primaryKey = 'idPers';
    protected $fillable = [
        'nom',
        'prenom',
        'dateNaissance',
        'lieuNaissance',
        'mobile',
        'phone',
        'typePersonne',
        'username',
        'password',
        'alanyaID',
        'photoURL',
        'idAdmin',
    ];

    protected $casts = [
        'dateNaissance' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'idAdmin', 'ID');
    }

    public function titulaires(): HasMany
    {
        return $this->hasMany(Titulaire::class, 'idPers', 'idPers');
    }

    public function enseignants(): HasMany
    {
        return $this->hasMany(Enseignant::class, 'idPers', 'idPers');
    }

    public function epreuves(): HasMany
    {
        return $this->hasMany(Epreuve::class, 'idPers', 'idPers');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class, 'idPers', 'idPers');
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'idPers', 'idPers');
    }

    public function residents(): HasMany
    {
        return $this->hasMany(Resident::class, 'idPers', 'idPers');
    }

    public function parents(): HasMany
    {
        return $this->hasMany(ParentEleve::class, 'idPers', 'idPers');
    }

    public function scopeParents($query)
    {
        return $query->where('typePersonne', '2');
    }
}
