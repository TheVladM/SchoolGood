<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Personne;
use App\Models\Quartier;
use App\Models\Resident;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    public function index()
    {
        return response()->json(Resident::with(['personne', 'quartier', 'admin'])->paginate(20));
    }

    public function create()
    {
        return response()->json([
            'personnes' => Personne::all(),
            'quartiers' => Quartier::all(),
            'admins' => Admin::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'idPers' => 'required|exists:personnes,idPers',
            'idQuartier' => 'required|exists:quartiers,idQuartier',
            'description' => 'nullable|string',
            'idAdmin' => 'nullable|exists:admins,ID',
        ]);

        $resident = Resident::create($data);

        return response()->json($resident, 201);
    }

    public function show(Resident $resident)
    {
        $resident->load(['personne', 'quartier', 'admin']);

        return response()->json($resident);
    }

    public function edit(Resident $resident)
    {
        return response()->json([
            'resident' => $resident,
            'personnes' => Personne::all(),
            'quartiers' => Quartier::all(),
            'admins' => Admin::all(),
        ]);
    }

    public function update(Request $request, Resident $resident)
    {
        $data = $request->validate([
            'idPers' => 'required|exists:personnes,idPers',
            'idQuartier' => 'required|exists:quartiers,idQuartier',
            'description' => 'nullable|string',
            'idAdmin' => 'nullable|exists:admins,ID',
        ]);

        $resident->update($data);

        return response()->json($resident);
    }

    public function destroy(Resident $resident)
    {
        $resident->delete();

        return response()->json(['message' => 'Resident supprimé.']);
    }
}
