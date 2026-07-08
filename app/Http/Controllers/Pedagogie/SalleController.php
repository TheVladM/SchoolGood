<?php

namespace App\Http\Controllers\Pedagogie;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Classe;
use App\Models\Salle;
use Illuminate\Http\Request;

class SalleController extends Controller
{
    public function index()
    {
        return response()->json(Salle::with(['classe', 'admin'])->paginate(20));
    }

    public function create()
    {
        return response()->json([
            'classes' => Classe::all(),
            'admins' => Admin::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'surface' => 'nullable|numeric',
            'idClasse' => 'required|exists:classes,idClasse',
            'actif' => 'boolean',
            'idAdmin' => 'nullable|exists:admins,ID',
        ]);

        $salle = Salle::create($data);

        return response()->json($salle, 201);
    }

    public function show(Salle $salle)
    {
        $salle->load(['classe', 'admin', 'frequentes', 'titulaires']);

        return response()->json($salle);
    }

    public function edit(Salle $salle)
    {
        return response()->json([
            'salle' => $salle,
            'classes' => Classe::all(),
            'admins' => Admin::all(),
        ]);
    }

    public function update(Request $request, Salle $salle)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'surface' => 'nullable|numeric',
            'idClasse' => 'required|exists:classes,idClasse',
            'actif' => 'boolean',
            'idAdmin' => 'nullable|exists:admins,ID',
        ]);

        $salle->update($data);

        return response()->json($salle);
    }

    public function destroy(Salle $salle)
    {
        $salle->delete();

        return response()->json(['message' => 'Salle supprimée.']);
    }
}
