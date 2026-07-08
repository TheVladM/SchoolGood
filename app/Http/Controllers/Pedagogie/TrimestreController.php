<?php

namespace App\Http\Controllers\Pedagogie;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AnneeAcademique;
use App\Models\Trimestre;
use Illuminate\Http\Request;

class TrimestreController extends Controller
{
    public function index()
    {
        return response()->json(Trimestre::with(['anneeAcademique', 'admin'])->paginate(20));
    }

    public function create()
    {
        return response()->json([
            'annees' => AnneeAcademique::all(),
            'admins' => Admin::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'periode' => 'nullable|string|max:255',
            'idAca' => 'required|exists:annee_academiques,idAnnee',
            'idAdmin' => 'nullable|exists:admins,ID',
        ]);

        $trimestre = Trimestre::create($data);

        return response()->json($trimestre, 201);
    }

    public function show(Trimestre $trimestre)
    {
        $trimestre->load(['anneeAcademique', 'admin', 'sessions']);

        return response()->json($trimestre);
    }

    public function edit(Trimestre $trimestre)
    {
        return response()->json([
            'trimestre' => $trimestre,
            'annees' => AnneeAcademique::all(),
            'admins' => Admin::all(),
        ]);
    }

    public function update(Request $request, Trimestre $trimestre)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'periode' => 'nullable|string|max:255',
            'idAca' => 'required|exists:annee_academiques,idAnnee',
            'idAdmin' => 'nullable|exists:admins,ID',
        ]);

        $trimestre->update($data);

        return response()->json($trimestre);
    }

    public function destroy(Trimestre $trimestre)
    {
        $trimestre->delete();

        return response()->json(['message' => 'Trimestre supprimé.']);
    }
}
