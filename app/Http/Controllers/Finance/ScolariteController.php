<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Cycle;
use App\Models\Personne;
use App\Models\Scolarite;
use Illuminate\Http\Request;

class ScolariteController extends Controller
{
    public function index()
    {
        return response()->json(Scolarite::with(['cycle', 'fondateur', 'tranches'])->paginate(20));
    }

    public function create()
    {
        return response()->json([
            'cycles' => Cycle::all(),
            'fondateurs' => Personne::where('typePersonne', 'fondateur')->orWhere('typePersonne', 'admin')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'inscription' => 'required|numeric',
            'pension' => 'required|numeric',
            'nbreTranche' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'idCycle' => 'required|exists:cycles,idCycle',
            'idFondateur' => 'nullable|exists:personnes,idPers',
        ]);

        $scolarite = Scolarite::create($data);

        return response()->json($scolarite, 201);
    }

    public function show(Scolarite $scolarite)
    {
        $scolarite->load(['cycle', 'fondateur', 'tranches']);

        return response()->json($scolarite);
    }

    public function edit(Scolarite $scolarite)
    {
        return response()->json([
            'scolarite' => $scolarite,
            'cycles' => Cycle::all(),
            'fondateurs' => Personne::where('typePersonne', 'fondateur')->orWhere('typePersonne', 'admin')->get(),
        ]);
    }

    public function update(Request $request, Scolarite $scolarite)
    {
        $data = $request->validate([
            'inscription' => 'required|numeric',
            'pension' => 'required|numeric',
            'nbreTranche' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'idCycle' => 'required|exists:cycles,idCycle',
            'idFondateur' => 'nullable|exists:personnes,idPers',
        ]);

        $scolarite->update($data);

        return response()->json($scolarite);
    }

    public function destroy(Scolarite $scolarite)
    {
        $scolarite->delete();

        return response()->json(['message' => 'Scolarité supprimée.']);
    }
}
