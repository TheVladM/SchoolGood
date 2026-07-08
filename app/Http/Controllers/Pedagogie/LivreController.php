<?php

namespace App\Http\Controllers\Pedagogie;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Livre;
use App\Models\Specialite;
use Illuminate\Http\Request;

class LivreController extends Controller
{
    public function index()
    {
        return response()->json(Livre::with(['specialite', 'admin'])->paginate(20));
    }

    public function create()
    {
        return response()->json([
            'specialites' => Specialite::all(),
            'admins' => Admin::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'auteurs' => 'nullable|string|max:255',
            'prix' => 'nullable|numeric',
            'idSpecialite' => 'nullable|exists:specialites,idSpecialite',
            'edition' => 'nullable|string|max:255',
            'annee_parution' => 'nullable|digits:4|integer',
            'idAdmin' => 'nullable|exists:admins,ID',
        ]);

        $livre = Livre::create($data);

        return response()->json($livre, 201);
    }

    public function show(Livre $livre)
    {
        $livre->load(['specialite', 'admin', 'cours']);

        return response()->json($livre);
    }

    public function edit(Livre $livre)
    {
        return response()->json([
            'livre' => $livre,
            'specialites' => Specialite::all(),
            'admins' => Admin::all(),
        ]);
    }

    public function update(Request $request, Livre $livre)
    {
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'auteurs' => 'nullable|string|max:255',
            'prix' => 'nullable|numeric',
            'idSpecialite' => 'nullable|exists:specialites,idSpecialite',
            'edition' => 'nullable|string|max:255',
            'annee_parution' => 'nullable|digits:4|integer',
            'idAdmin' => 'nullable|exists:admins,ID',
        ]);

        $livre->update($data);

        return response()->json($livre);
    }

    public function destroy(Livre $livre)
    {
        $livre->delete();

        return response()->json(['message' => 'Livre supprimé.']);
    }
}
