<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AnneeAcademique;
use App\Models\Cours;
use App\Models\Eleve;
use App\Models\Mode;
use App\Models\Paiement;
use App\Models\Personne;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    public function index()
    {
        return response()->json(Paiement::with(['eleve', 'anneeAcademique', 'mode', 'personne'])->paginate(20));
    }

    public function create()
    {
        return response()->json([
            'eleves' => Eleve::all(),
            'annees' => AnneeAcademique::all(),
            'modes' => Mode::all(),
            'personnes' => Personne::all(),
            'admins' => Admin::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'matricule' => 'required|exists:eleves,matricule',
            'idAca' => 'required|exists:annee_academiques,idAnnee',
            'montant' => 'required|numeric',
            'url' => 'nullable|url|max:500',
            'comentaire' => 'nullable|string',
            'idMode' => 'required|exists:modes,idMode',
            'operation_ID' => 'nullable|string|max:255',
            'idPers' => 'nullable|exists:personnes,idPers',
            'datePaie' => 'nullable|date',
            'dateEnregistrer' => 'nullable|date',
        ]);

        $paiement = Paiement::create($data);

        return response()->json($paiement, 201);
    }

    public function show(Paiement $paiement)
    {
        $paiement->load(['eleve', 'anneeAcademique', 'mode', 'personne']);

        return response()->json($paiement);
    }

    public function edit(Paiement $paiement)
    {
        return response()->json([
            'paiement' => $paiement,
            'eleves' => Eleve::all(),
            'annees' => AnneeAcademique::all(),
            'modes' => Mode::all(),
            'personnes' => Personne::all(),
            'admins' => Admin::all(),
        ]);
    }

    public function update(Request $request, Paiement $paiement)
    {
        $data = $request->validate([
            'matricule' => 'required|exists:eleves,matricule',
            'idAca' => 'required|exists:annee_academiques,idAnnee',
            'montant' => 'required|numeric',
            'url' => 'nullable|url|max:500',
            'comentaire' => 'nullable|string',
            'idMode' => 'required|exists:modes,idMode',
            'operation_ID' => 'nullable|string|max:255',
            'idPers' => 'nullable|exists:personnes,idPers',
            'datePaie' => 'nullable|date',
            'dateEnregistrer' => 'nullable|date',
        ]);

        $paiement->update($data);

        return response()->json($paiement);
    }

    public function destroy(Paiement $paiement)
    {
        $paiement->delete();

        return response()->json(['message' => 'Paiement supprimé.']);
    }
}
