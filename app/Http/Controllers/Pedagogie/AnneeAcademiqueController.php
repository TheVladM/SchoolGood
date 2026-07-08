<?php

namespace App\Http\Controllers\Pedagogie;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AnneeAcademique;
use Illuminate\Http\Request;

class AnneeAcademiqueController extends Controller
{
    public function index()
    {
        return response()->json(AnneeAcademique::with(['admin', 'trimestres'])->paginate(20));
    }

    public function create()
    {
        return response()->json(['admins' => Admin::all()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'periode' => 'nullable|string|max:255',
            'idAdmin' => 'nullable|exists:admins,ID',
        ]);

        $annee = AnneeAcademique::create($data);

        return response()->json($annee, 201);
    }

    public function show(AnneeAcademique $anneeAcademique)
    {
        $anneeAcademique->load(['admin', 'trimestres', 'rapports']);

        return response()->json($anneeAcademique);
    }

    public function edit(AnneeAcademique $anneeAcademique)
    {
        return response()->json([
            'anneeAcademique' => $anneeAcademique,
            'admins' => Admin::all(),
        ]);
    }

    public function update(Request $request, AnneeAcademique $anneeAcademique)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'periode' => 'nullable|string|max:255',
            'idAdmin' => 'nullable|exists:admins,ID',
        ]);

        $anneeAcademique->update($data);

        return response()->json($anneeAcademique);
    }

    public function destroy(AnneeAcademique $anneeAcademique)
    {
        $anneeAcademique->delete();

        return response()->json(['message' => 'Année académique supprimée.']);
    }
}
