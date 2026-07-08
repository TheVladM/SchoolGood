<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Discipline;
use Illuminate\Http\Request;

class DisciplineController extends Controller
{
    public function index()
    {
        return response()->json(Discipline::paginate(20));
    }

    public function create()
    {
        return response()->json([]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'points' => 'required|integer|min:0',
        ]);

        $discipline = Discipline::create($data);

        return response()->json($discipline, 201);
    }

    public function show(Discipline $discipline)
    {
        return response()->json($discipline);
    }

    public function edit(Discipline $discipline)
    {
        return response()->json(['discipline' => $discipline]);
    }

    public function update(Request $request, Discipline $discipline)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'points' => 'required|integer|min:0',
        ]);

        $discipline->update($data);

        return response()->json($discipline);
    }

    public function destroy(Discipline $discipline)
    {
        $discipline->delete();

        return response()->json(['message' => 'Discipline supprimée.']);
    }
}
