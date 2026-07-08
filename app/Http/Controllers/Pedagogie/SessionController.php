<?php

namespace App\Http\Controllers\Pedagogie;

use App\Http\Controllers\Controller;
use App\Models\Personne;
use App\Models\Session;
use App\Models\Trimestre;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function index()
    {
        return response()->json(Session::with(['trimestre', 'personne', 'evaluations'])->paginate(20));
    }

    public function create()
    {
        return response()->json([
            'trimestres' => Trimestre::all(),
            'personnes' => Personne::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'description' => 'nullable|string',
            'idTrimestre' => 'required|exists:trimestres,idTrimes',
            'idPers' => 'nullable|exists:personnes,idPers',
        ]);

        $session = Session::create($data);

        return response()->json($session, 201);
    }

    public function show(Session $session)
    {
        $session->load(['trimestre', 'personne', 'evaluations']);

        return response()->json($session);
    }

    public function edit(Session $session)
    {
        return response()->json([
            'session' => $session,
            'trimestres' => Trimestre::all(),
            'personnes' => Personne::all(),
        ]);
    }

    public function update(Request $request, Session $session)
    {
        $data = $request->validate([
            'libelle' => 'required|string|max:255',
            'description' => 'nullable|string',
            'idTrimestre' => 'required|exists:trimestres,idTrimes',
            'idPers' => 'nullable|exists:personnes,idPers',
        ]);

        $session->update($data);

        return response()->json($session);
    }

    public function destroy(Session $session)
    {
        $session->delete();

        return response()->json(['message' => 'Session supprimée.']);
    }
}
