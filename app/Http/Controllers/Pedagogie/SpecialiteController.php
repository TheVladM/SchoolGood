<?php

namespace App\Http\Controllers\Pedagogie;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Specialite;
use Illuminate\Http\Request;

class SpecialiteController extends Controller
{
    public function index()
    {
        return response()->json(Specialite::with(['admin', 'livres'])->paginate(20));
    }

    public function create()
    {
        return response()->json(['admins' => Admin::all()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'idAdmin' => 'nullable|exists:admins,ID',
        ]);

        $specialite = Specialite::create($data);

        return response()->json($specialite, 201);
    }

    public function show(Specialite $specialite)
    {
        $specialite->load(['admin', 'livres']);

        return response()->json($specialite);
    }

    public function edit(Specialite $specialite)
    {
        return response()->json([
            'specialite' => $specialite,
            'admins' => Admin::all(),
        ]);
    }

    public function update(Request $request, Specialite $specialite)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'idAdmin' => 'nullable|exists:admins,ID',
        ]);

        $specialite->update($data);

        return response()->json($specialite);
    }

    public function destroy(Specialite $specialite)
    {
        $specialite->delete();

        return response()->json(['message' => 'Spécialité supprimée.']);
    }
}
