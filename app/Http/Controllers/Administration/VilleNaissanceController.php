<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\VilleNaissance;
use Illuminate\Http\Request;

class VilleNaissanceController extends Controller
{
    public function index()
    {
        return response()->json(VilleNaissance::paginate(20));
    }

    public function create()
    {
        return response()->json([]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'actif' => 'boolean',
        ]);

        $ville = VilleNaissance::create($data);

        return response()->json($ville, 201);
    }

    public function show(VilleNaissance $villeNaissance)
    {
        return response()->json($villeNaissance);
    }

    public function edit(VilleNaissance $villeNaissance)
    {
        return response()->json(['villeNaissance' => $villeNaissance]);
    }

    public function update(Request $request, VilleNaissance $villeNaissance)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'actif' => 'boolean',
        ]);

        $villeNaissance->update($data);

        return response()->json($villeNaissance);
    }

    public function destroy(VilleNaissance $villeNaissance)
    {
        $villeNaissance->delete();

        return response()->json(['message' => 'Ville de naissance supprimée.']);
    }
}
