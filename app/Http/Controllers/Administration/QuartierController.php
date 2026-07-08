<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Quartier;
use Illuminate\Http\Request;

class QuartierController extends Controller
{
    public function index()
    {
        return response()->json(Quartier::paginate(20));
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

        $quartier = Quartier::create($data);

        return response()->json($quartier, 201);
    }

    public function show(Quartier $quartier)
    {
        return response()->json($quartier);
    }

    public function edit(Quartier $quartier)
    {
        return response()->json(['quartier' => $quartier]);
    }

    public function update(Request $request, Quartier $quartier)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $quartier->update($data);

        return response()->json($quartier);
    }

    public function destroy(Quartier $quartier)
    {
        $quartier->delete();

        return response()->json(['message' => 'Quartier supprimé.']);
    }
}
