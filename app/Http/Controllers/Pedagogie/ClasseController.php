<?php

namespace App\Http\Controllers\Pedagogie;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Classe;
use App\Models\Cycle;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    public function index()
    {
        return response()->json(Classe::with(['cycle', 'admin'])->paginate(20));
    }

    public function create()
    {
        return response()->json([
            'cycles' => Cycle::all(),
            'admins' => Admin::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'idCycle' => 'required|exists:cycles,idCycle',
            'idAdmin' => 'nullable|exists:admins,ID',
        ]);

        $classe = Classe::create($data);

        return response()->json($classe, 201);
    }

    public function show(Classe $classe)
    {
        $classe->load(['cycle', 'admin', 'salles']);

        return response()->json($classe);
    }

    public function edit(Classe $classe)
    {
        return response()->json([
            'classe' => $classe,
            'cycles' => Cycle::all(),
            'admins' => Admin::all(),
        ]);
    }

    public function update(Request $request, Classe $classe)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'idCycle' => 'required|exists:cycles,idCycle',
            'idAdmin' => 'nullable|exists:admins,ID',
        ]);

        $classe->update($data);

        return response()->json($classe);
    }

    public function destroy(Classe $classe)
    {
        $classe->delete();

        return response()->json(['message' => 'Classe supprimée.']);
    }
}
