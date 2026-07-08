<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Mode;
use App\Models\Personne;
use Illuminate\Http\Request;

class ModeController extends Controller
{
    public function index()
    {
        return response()->json(Mode::with(['fondateur', 'paiements'])->paginate(20));
    }

    public function create()
    {
        return response()->json(['fondateurs' => Personne::where('typePersonne', 'fondateur')->orWhere('typePersonne', 'admin')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'information' => 'nullable|string',
            'actif' => 'boolean',
            'idFondateur' => 'nullable|exists:personnes,idPers',
        ]);

        $mode = Mode::create($data);

        return response()->json($mode, 201);
    }

    public function show(Mode $mode)
    {
        $mode->load(['fondateur', 'paiements']);

        return response()->json($mode);
    }

    public function edit(Mode $mode)
    {
        return response()->json([
            'mode' => $mode,
            'fondateurs' => Personne::where('typePersonne', 'fondateur')->orWhere('typePersonne', 'admin')->get(),
        ]);
    }

    public function update(Request $request, Mode $mode)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'information' => 'nullable|string',
            'actif' => 'boolean',
            'idFondateur' => 'nullable|exists:personnes,idPers',
        ]);

        $mode->update($data);

        return response()->json($mode);
    }

    public function destroy(Mode $mode)
    {
        $mode->delete();

        return response()->json(['message' => 'Mode de paiement supprimé.']);
    }
}
