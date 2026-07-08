<?php

namespace App\Http\Controllers\Pedagogie;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Cycle;
use Illuminate\Http\Request;

class CycleController extends Controller
{
    public function index()
    {
        return response()->json(Cycle::with(['admin', 'classes', 'scolarites'])->paginate(20));
    }

    public function create()
    {
        return response()->json(['admins' => Admin::all()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'description' => 'nullable|string',
            'idAdmin' => 'nullable|exists:admins,ID',
        ]);

        $cycle = Cycle::create($data);

        return response()->json($cycle, 201);
    }

    public function show(Cycle $cycle)
    {
        $cycle->load(['admin', 'classes', 'scolarites']);

        return response()->json($cycle);
    }

    public function edit(Cycle $cycle)
    {
        return response()->json([
            'cycle' => $cycle,
            'admins' => Admin::all(),
        ]);
    }

    public function update(Request $request, Cycle $cycle)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'description' => 'nullable|string',
            'idAdmin' => 'nullable|exists:admins,ID',
        ]);

        $cycle->update($data);

        return response()->json($cycle);
    }

    public function destroy(Cycle $cycle)
    {
        $cycle->delete();

        return response()->json(['message' => 'Cycle supprimé.']);
    }
}
