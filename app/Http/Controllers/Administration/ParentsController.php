<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreParentRequest;
use App\Http\Requests\UpdateParentRequest;
use App\Models\Admin;
use App\Models\Eleve;
use App\Models\ParentEleve;
use App\Models\Personne;

class ParentsController extends Controller
{
    public function index()
    {
        return response()->json(ParentEleve::with(['personne', 'eleve.frequences.salle.classe', 'admin'])->paginate(20));
    }

    public function create()
    {
        return response()->json([
            'personnes' => Personne::where('typePersonne', '2')->get(),
            'eleves' => Eleve::all(),
            'admins' => Admin::all(),
        ]);
    }

    public function store(StoreParentRequest $request)
    {
        $data = $request->validated();
        $parent = ParentEleve::create($data);
        $parent->load(['personne', 'eleve', 'admin']);

        return response()->json($parent, 201);
    }

    public function show(ParentEleve $parent)
    {
        $parent->load(['personne', 'eleve', 'admin']);

        return response()->json($parent);
    }

    public function edit(ParentEleve $parent)
    {
        return response()->json([
            'parent' => $parent,
            'personnes' => Personne::where('typePersonne', '2')->get(),
            'eleves' => Eleve::all(),
            'admins' => Admin::all(),
        ]);
    }

    public function update(UpdateParentRequest $request, ParentEleve $parent)
    {
        $data = $request->validated();
        $parent->update($data);
        $parent->load(['personne', 'eleve', 'admin']);

        return response()->json($parent);
    }

    public function destroy(ParentEleve $parent)
    {
        $parent->delete();

        return response()->json(['message' => 'Parent supprimé.']);
    }
}
