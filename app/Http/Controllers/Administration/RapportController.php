<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AnneeAcademique;
use App\Models\Eleve;
use App\Models\Personne;
use App\Models\Rapport;
use Illuminate\Http\Request;

class RapportController extends Controller
{
    public function index()
    {
        return response()->json(Rapport::with(['eleve', 'anneeAcademique', 'personne', 'admin'])->paginate(20));
    }

    public function create()
    {
        return response()->json([
            'eleves' => Eleve::all(),
            'annees' => AnneeAcademique::all(),
            'personnes' => Personne::all(),
            'admins' => Admin::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'points' => 'required|integer',
            'matricule' => 'required|exists:eleves,matricule',
            'idAca' => 'required|exists:annee_academiques,idAnnee',
            'commentaire' => 'nullable|string',
            'event_date' => 'nullable|date',
            'idPers' => 'nullable|exists:personnes,idPers',
            'idAdmin' => 'nullable|exists:admins,ID',
        ]);

        $rapport = Rapport::create($data);

        return response()->json($rapport, 201);
    }

    public function show(Rapport $rapport)
    {
        $rapport->load(['eleve', 'anneeAcademique', 'personne', 'admin']);

        return response()->json($rapport);
    }

    public function edit(Rapport $rapport)
    {
        return response()->json([
            'rapport' => $rapport,
            'eleves' => Eleve::all(),
            'annees' => AnneeAcademique::all(),
            'personnes' => Personne::all(),
            'admins' => Admin::all(),
        ]);
    }

    public function update(Request $request, Rapport $rapport)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'points' => 'required|integer',
            'matricule' => 'required|exists:eleves,matricule',
            'idAca' => 'required|exists:annee_academiques,idAnnee',
            'commentaire' => 'nullable|string',
            'event_date' => 'nullable|date',
            'idPers' => 'nullable|exists:personnes,idPers',
            'idAdmin' => 'nullable|exists:admins,ID',
        ]);

        $rapport->update($data);

        return response()->json($rapport);
    }

    public function destroy(Rapport $rapport)
    {
        $rapport->delete();

        return response()->json(['message' => 'Rapport supprimé.']);
    }
}
