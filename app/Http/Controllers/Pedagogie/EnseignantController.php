<?php

namespace App\Http\Controllers\Pedagogie;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Cours;
use App\Models\Enseignant;
use App\Models\Personne;
use Illuminate\Http\Request;

class EnseignantController extends Controller
{
    public function index()
    {
        return response()->json(Enseignant::with(['personne', 'cours', 'admin'])->paginate(20));
    }

    public function create()
    {
        return response()->json([
            'personnes' => Personne::all(),
            'cours' => Cours::all(),
            'admins' => Admin::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'idPers' => 'required|exists:personnes,idPers',
            'idCours' => 'nullable|exists:cours,idCours',
            'actif' => 'boolean',
            'idAdmin' => 'nullable|exists:admins,ID',
        ]);

        $enseignant = Enseignant::create($data);

        return response()->json($enseignant, 201);
    }

    public function show(Enseignant $enseignant)
    {
        $enseignant->load(['personne', 'cours', 'admin']);

        return response()->json($enseignant);
    }

    public function edit(Enseignant $enseignant)
    {
        return response()->json([
            'enseignant' => $enseignant,
            'personnes' => Personne::all(),
            'cours' => Cours::all(),
            'admins' => Admin::all(),
        ]);
    }

    public function update(Request $request, Enseignant $enseignant)
    {
        $data = $request->validate([
            'idPers' => 'required|exists:personnes,idPers',
            'idCours' => 'nullable|exists:cours,idCours',
            'actif' => 'boolean',
            'idAdmin' => 'nullable|exists:admins,ID',
        ]);

        $enseignant->update($data);

        return response()->json($enseignant);
    }

    public function destroy(Enseignant $enseignant)
    {
        $enseignant->delete();

        return response()->json(['message' => 'Enseignant supprimé.']);
    }
}
