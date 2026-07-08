<?php

namespace App\Http\Controllers\Pedagogie;

use App\Http\Controllers\Controller;
use App\Models\NatureEpreuve;
use Illuminate\Http\Request;

class NatureEpreuveController extends Controller
{
    public function index()
    {
        return response()->json(NatureEpreuve::with('epreuves')->paginate(20));
    }

    public function create()
    {
        return response()->json([]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $nature = NatureEpreuve::create($data);

        return response()->json($nature, 201);
    }

    public function show(NatureEpreuve $natureEpreuve)
    {
        $natureEpreuve->load('epreuves');

        return response()->json($natureEpreuve);
    }

    public function edit(NatureEpreuve $natureEpreuve)
    {
        return response()->json(['natureEpreuve' => $natureEpreuve]);
    }

    public function update(Request $request, NatureEpreuve $natureEpreuve)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $natureEpreuve->update($data);

        return response()->json($natureEpreuve);
    }

    public function destroy(NatureEpreuve $natureEpreuve)
    {
        $natureEpreuve->delete();

        return response()->json(['message' => 'Nature d\'épreuve supprimée.']);
    }
}
