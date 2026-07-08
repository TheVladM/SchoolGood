<?php

namespace App\Http\Controllers\Pedagogie;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Cours;
use App\Models\Livre;
use Illuminate\Http\Request;

class CoursController extends Controller
{
    public function index()
    {
        return response()->json(Cours::with(['livre', 'admin', 'enseignants'])->paginate(20));
    }

    public function create()
    {
        return response()->json([
            'livres' => Livre::all(),
            'admins' => Admin::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'note' => 'nullable|numeric',
            'coefficient' => 'nullable|numeric',
            'description' => 'nullable|string',
            'idLivre' => 'nullable|exists:livres,idLivre',
            'actif' => 'boolean',
            'idAdmin' => 'nullable|exists:admins,ID',
        ]);

        $cours = Cours::create($data);

        return response()->json($cours, 201);
    }

    public function show(Cours $cours)
    {
        $cours->load(['livre', 'admin', 'enseignants', 'evaluations']);

        return response()->json($cours);
    }

    public function edit(Cours $cours)
    {
        return response()->json([
            'cours' => $cours,
            'livres' => Livre::all(),
            'admins' => Admin::all(),
        ]);
    }

    public function update(Request $request, Cours $cours)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'note' => 'nullable|numeric',
            'coefficient' => 'nullable|numeric',
            'description' => 'nullable|string',
            'idLivre' => 'nullable|exists:livres,idLivre',
            'actif' => 'boolean',
            'idAdmin' => 'nullable|exists:admins,ID',
        ]);

        $cours->update($data);

        return response()->json($cours);
    }

    public function destroy(Cours $cours)
    {
        $cours->delete();

        return response()->json(['message' => 'Cours supprimé.']);
    }
}
