<?php

namespace App\Http\Controllers\Pedagogie;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Personne;
use App\Models\Salle;
use App\Models\Titulaire;
use Illuminate\Http\Request;

class TitulaireController extends Controller
{
    public function index()
    {
        return response()->json(Titulaire::with(['personne', 'salle', 'admin'])->paginate(20));
    }

    public function create()
    {
        return response()->json([
            'personnes' => Personne::all(),
            'salles' => Salle::all(),
            'admins' => Admin::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'idPers' => 'required|exists:personnes,idPers',
            'idSalle' => 'required|exists:salles,idSalle',
            'actif' => 'boolean',
            'idAdmin' => 'nullable|exists:admins,ID',
        ]);

        $titulaire = Titulaire::create($data);

        return response()->json($titulaire, 201);
    }

    public function show(Titulaire $titulaire)
    {
        $titulaire->load(['personne', 'salle', 'admin']);

        return response()->json($titulaire);
    }

    public function edit(Titulaire $titulaire)
    {
        return response()->json([
            'titulaire' => $titulaire,
            'personnes' => Personne::all(),
            'salles' => Salle::all(),
            'admins' => Admin::all(),
        ]);
    }

    public function update(Request $request, Titulaire $titulaire)
    {
        $data = $request->validate([
            'idPers' => 'required|exists:personnes,idPers',
            'idSalle' => 'required|exists:salles,idSalle',
            'actif' => 'boolean',
            'idAdmin' => 'nullable|exists:admins,ID',
        ]);

        $titulaire->update($data);

        return response()->json($titulaire);
    }

    public function destroy(Titulaire $titulaire)
    {
        $titulaire->delete();

        return response()->json(['message' => 'Titulaire supprimé.']);
    }
}
