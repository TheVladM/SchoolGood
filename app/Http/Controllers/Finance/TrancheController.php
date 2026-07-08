<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Personne;
use App\Models\Scolarite;
use App\Models\Tranche;
use Illuminate\Http\Request;

class TrancheController extends Controller
{
    public function index()
    {
        return response()->json(Tranche::with(['scolarite', 'fondateur'])->paginate(20));
    }

    public function create()
    {
        return response()->json([
            'scolarites' => Scolarite::all(),
            'fondateurs' => Personne::where('typePersonne', 'fondateur')->orWhere('typePersonne', 'admin')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'montant' => 'required|numeric',
            'delai_mois' => 'required|integer|min:0',
            'delai_jour' => 'required|integer|min:0',
            'idScolarite' => 'required|exists:scolarites,idScolarite',
            'actif' => 'boolean',
            'idFondateur' => 'nullable|exists:personnes,idPers',
        ]);

        $tranche = Tranche::create($data);

        return response()->json($tranche, 201);
    }

    public function show(Tranche $tranche)
    {
        $tranche->load(['scolarite', 'fondateur']);

        return response()->json($tranche);
    }

    public function edit(Tranche $tranche)
    {
        return response()->json([
            'tranche' => $tranche,
            'scolarites' => Scolarite::all(),
            'fondateurs' => Personne::where('typePersonne', 'fondateur')->orWhere('typePersonne', 'admin')->get(),
        ]);
    }

    public function update(Request $request, Tranche $tranche)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'montant' => 'required|numeric',
            'delai_mois' => 'required|integer|min:0',
            'delai_jour' => 'required|integer|min:0',
            'idScolarite' => 'required|exists:scolarites,idScolarite',
            'actif' => 'boolean',
            'idFondateur' => 'nullable|exists:personnes,idPers',
        ]);

        $tranche->update($data);

        return response()->json($tranche);
    }

    public function destroy(Tranche $tranche)
    {
        $tranche->delete();

        return response()->json(['message' => 'Tranche supprimée.']);
    }
}
