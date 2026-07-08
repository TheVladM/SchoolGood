<?php

namespace App\Http\Controllers\Pedagogie;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEleveRequest;
use App\Http\Requests\UpdateEleveRequest;
use App\Http\Traits\ReusablePhotoUpload;
use App\Models\Admin;
use App\Models\Eleve;
use App\Models\VilleNaissance;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class EleveController extends Controller
{
    use ReusablePhotoUpload;

    public function index(Request $request)
    {
        $eleves = Eleve::with(['villeNaissance', 'admin', 'frequentes.salle.classe'])
            ->when($request->query('annee'), function ($query, $annee): void {
                $query->whereHas('frequentes', function ($subQuery) use ($annee) {
                    $subQuery->where('idAcademi', $annee);
                });
            })
            ->when($request->query('classe'), function ($query, $classe): void {
                $query->whereHas('frequentes.salle', function ($subQuery) use ($classe) {
                    $subQuery->where('idClasse', $classe);
                });
            })
            ->when($request->query('q'), function ($query, $q): void {
                $query->where(function ($subQuery) use ($q) {
                    $subQuery->where('matricule', $q)
                        ->orWhere('nom', 'like', "%{$q}%")
                        ->orWhere('prenom', 'like', "%{$q}%");
                });
            })
            ->paginate(20);

        return response()->json($eleves);
    }

    public function create()
    {
        return response()->json([
            'villes' => VilleNaissance::all(),
            'admins' => Admin::all(),
        ]);
    }

    public function store(StoreEleveRequest $request)
    {
        $data = $request->validated();
        $photoURL = $this->uploadPhoto($request, 'photo', new Eleve(), 'photoURL', 'photos/eleves');

        $data = Arr::except($data, ['photo']);

        if ($photoURL !== null) {
            $data['photoURL'] = $photoURL;
        }

        $eleve = Eleve::create($data);

        return response()->json($eleve, 201);
    }

    public function show(Eleve $eleve)
    {
        $eleve->load(['villeNaissance', 'admin', 'frequentes.salle.classe']);

        return response()->json($eleve);
    }

    public function edit(Eleve $eleve)
    {
        return response()->json([
            'eleve' => $eleve,
            'villes' => VilleNaissance::all(),
            'admins' => Admin::all(),
        ]);
    }

    public function update(UpdateEleveRequest $request, Eleve $eleve)
    {
        $data = $request->validated();
        $photoURL = $this->uploadPhoto($request, 'photo', $eleve, 'photoURL', 'photos/eleves');

        $data = Arr::except($data, ['photo']);

        if ($photoURL !== null) {
            $data['photoURL'] = $photoURL;
        }

        $eleve->update($data);

        return response()->json($eleve);
    }

    public function deletePhoto(Eleve $eleve)
    {
        $this->deleteStoredPhoto($eleve, 'photoURL', 'public');
        $eleve->update(['photoURL' => null]);

        return response()->json(['message' => 'Photo de l\'élève supprimée.']);
    }

    public function search(Request $request)
    {
        $q = trim($request->query('q', ''));

        if ($q === '') {
            return response()->json([]);
        }

        $eleves = Eleve::with(['frequentes.salle.classe'])
            ->where(function ($query) use ($q) {
                $query->where('matricule', $q)
                    ->orWhere('nom', 'like', "%{$q}%")
                    ->orWhere('prenom', 'like', "%{$q}%");
            })
            ->limit(10)
            ->get()
            ->map(function (Eleve $eleve) {
                return [
                    'matricule' => $eleve->matricule,
                    'nom' => $eleve->nom,
                    'prenom' => $eleve->prenom,
                    'photoURL' => $eleve->photoURL,
                    'classe' => optional($eleve->frequentes->first()?->salle?->classe)->libelle,
                ];
            });

        return response()->json($eleves);
    }

    public function destroy(Eleve $eleve)
    {
        $this->deleteStoredPhoto($eleve, 'photoURL', 'public');
        $eleve->delete();

        return response()->json(['message' => 'Élève supprimé.']);
    }
}
