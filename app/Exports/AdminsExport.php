<?php

namespace App\Exports;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AdminsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected ?string $typeAdmin;
    protected ?string $actif;

    public function __construct(?string $typeAdmin = null, ?string $actif = null)
    {
        $this->typeAdmin = $typeAdmin;
        $this->actif = $actif;
    }

    public function collection(): Collection
    {
        $query = Admin::query();

        if ($this->typeAdmin) {
            $query->where('typeAdmin', $this->typeAdmin);
        }

        if ($this->actif !== null && $this->actif !== '') {
            $query->where('actif', (bool) $this->actif);
        }

        return $query->get(['ID', 'nom', 'username', 'typeAdmin', 'mobile', 'alanyaID', 'actif']);
    }

    public function headings(): array
    {
        return [
            'N°',
            'Nom',
            'Prénom / Nom d’utilisateur',
            'Rôle',
            'Mobile',
            'AlanyaID',
            'Statut actif',
        ];
    }
}
