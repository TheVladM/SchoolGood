<?php

namespace App\Http\Controllers\Pedagogie;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Epreuve;
use App\Models\NatureEpreuve;
use App\Models\Personne;
use Illuminate\Http\Request;

class EpreuveController extends Controller
{
    public function index()
    {
        return response()->json(Epreuve::with(['nature', 'personne'])->paginate(20));
    }

    public function create()
    {
        return response()->json([
            'natures' => NatureEpreuve::all(),
            'personnes' => Personne::all(),
            'admins' => Admin::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'urlDoc' => 'nullable|url|max:500',
            'auteur' => 'nullable|string|max:255',
            'idNature' => 'required|exists:nature_epreuves,idNature',
            'idPers' => 'nullable|exists:personnes,idPers',
        ]);

        $epreuve = Epreuve::create($data);

        return response()->json($epreuve, 201);
    }

    public function show(Epreuve $epreuve)
    {
        $epreuve->load(['nature', 'personne', 'evaluations']);

        return response()->json($epreuve);
    }

    public function edit(Epreuve $epreuve)
    {
        return response()->json([
            'epreuve' => $epreuve,
            'natures' => NatureEpreuve::all(),
            'personnes' => Personne::all(),
        ]);
    }

    public function update(Request $request, Epreuve $epreuve)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'urlDoc' => 'nullable|url|max:500',
            'auteur' => 'nullable|string|max:255',
            'idNature' => 'required|exists:nature_epreuves,idNature',
            'idPers' => 'nullable|exists:personnes,idPers',
        ]);

        $epreuve->update($data);

        return response()->json($epreuve);
    }

    public function destroy(Epreuve $epreuve)
    {
        $epreuve->delete();

        return response()->json(['message' => 'Épreuve supprimée.']);
    }
}
