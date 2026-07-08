<?php

namespace App\Exports;

use App\Models\Eleve;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ElevesExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected ?int $idClasse;
    protected ?int $idAnnee;
    protected ?string $actif;

    public function __construct(?int $idClasse = null, ?int $idAnnee = null, ?string $actif = null)
    {
        $this->idClasse = $idClasse;
        $this->idAnnee = $idAnnee;
        $this->actif = $actif;
    }

    public function collection(): Collection
    {
        $query = Eleve::with(['frequentes.salle.classe']);

        if ($this->idAnnee) {
            $query->whereHas('frequentes', function ($query) {
                $query->where('idAcademi', $this->idAnnee);
            });
        }

        if ($this->idClasse) {
            $query->whereHas('frequentes.salle', function ($query) {
                $query->where('idClasse', $this->idClasse);
            });
        }

        if ($this->actif !== null && $this->actif !== '') {
            $query->where('actif', (bool) $this->actif);
        }

        return $query->get()->map(function (Eleve $eleve) {
            $classe = optional($eleve->frequentes->first()?->salle?->classe)->libelle;

            return [
                'matricule' => $eleve->matricule,
                'nom' => $eleve->nom,
                'prenom' => $eleve->prenom,
                'sexe' => $eleve->sexe,
                'classe' => $classe,
                'anneeAcademique' => optional($eleve->frequentes->first())->idAcademi,
                'statut' => $eleve->actif ? 'Actif' : 'Inactif',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Matricule',
            'Nom',
            'Prénom',
            'Sexe',
            'Classe',
            'Année académique',
            'Statut',
        ];
    }
}
