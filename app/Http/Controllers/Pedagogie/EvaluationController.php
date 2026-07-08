<?php

namespace App\Http\Controllers\Pedagogie;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\Epreuve;
use App\Models\Eleve;
use App\Models\Evaluation;
use App\Models\Personne;
use App\Models\Session;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function index()
    {
        return response()->json(Evaluation::with(['eleve', 'epreuve', 'cours', 'session', 'personne'])->paginate(20));
    }

    public function create()
    {
        return response()->json([
            'eleves' => Eleve::all(),
            'epreuves' => Epreuve::all(),
            'cours' => Cours::all(),
            'sessions' => Session::all(),
            'personnes' => Personne::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'note' => 'nullable|numeric',
            'appreciation' => 'nullable|string',
            'matricule' => 'required|exists:eleves,matricule',
            'idEpreuve' => 'required|exists:epreuves,idEpreuve',
            'idCours' => 'required|exists:cours,idCours',
            'idSession' => 'required|exists:sessions,idSession',
            'idPers' => 'nullable|exists:personnes,idPers',
        ]);

        $evaluation = Evaluation::create($data);

        return response()->json($evaluation, 201);
    }

    public function show(Evaluation $evaluation)
    {
        $evaluation->load(['eleve', 'epreuve', 'cours', 'session', 'personne']);

        return response()->json($evaluation);
    }

    public function edit(Evaluation $evaluation)
    {
        return response()->json([
            'evaluation' => $evaluation,
            'eleves' => Eleve::all(),
            'epreuves' => Epreuve::all(),
            'cours' => Cours::all(),
            'sessions' => Session::all(),
            'personnes' => Personne::all(),
        ]);
    }

    public function update(Request $request, Evaluation $evaluation)
    {
        $data = $request->validate([
            'note' => 'nullable|numeric',
            'appreciation' => 'nullable|string',
            'matricule' => 'required|exists:eleves,matricule',
            'idEpreuve' => 'required|exists:epreuves,idEpreuve',
            'idCours' => 'required|exists:cours,idCours',
            'idSession' => 'required|exists:sessions,idSession',
            'idPers' => 'nullable|exists:personnes,idPers',
        ]);

        $evaluation->update($data);

        return response()->json($evaluation);
    }

    public function destroy(Evaluation $evaluation)
    {
        $evaluation->delete();

        return response()->json(['message' => 'Évaluation supprimée.']);
    }
}
