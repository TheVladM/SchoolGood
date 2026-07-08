<?php

namespace App\Http\Controllers\Pedagogie;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Classe;
use App\Models\Cours;
use App\Models\EmploiDuTemps;
use Illuminate\Http\Request;

class EmploiDuTempsController extends Controller
{
    public function index()
    {
        return response()->json(EmploiDuTemps::with(['classe', 'cours', 'admin'])->paginate(20));
    }

    public function create()
    {
        return response()->json([
            'classes' => Classe::all(),
            'cours' => Cours::all(),
            'admins' => Admin::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jour' => 'required|string|max:255',
            'heure' => 'required|date_format:H:i',
            'idClasse' => 'required|exists:classes,idClasse',
            'idCours' => 'required|exists:cours,idCours',
            'idAdmin' => 'nullable|exists:admins,ID',
        ]);

        $emploi = EmploiDuTemps::create($data);

        return response()->json($emploi, 201);
    }

    public function show(EmploiDuTemps $emploiDuTemps)
    {
        $emploiDuTemps->load(['classe', 'cours', 'admin']);

        return response()->json($emploiDuTemps);
    }

    public function edit(EmploiDuTemps $emploiDuTemps)
    {
        return response()->json([
            'emploiDuTemps' => $emploiDuTemps,
            'classes' => Classe::all(),
            'cours' => Cours::all(),
            'admins' => Admin::all(),
        ]);
    }

    public function update(Request $request, EmploiDuTemps $emploiDuTemps)
    {
        $data = $request->validate([
            'jour' => 'required|string|max:255',
            'heure' => 'required|date_format:H:i',
            'idClasse' => 'required|exists:classes,idClasse',
            'idCours' => 'required|exists:cours,idCours',
            'idAdmin' => 'nullable|exists:admins,ID',
        ]);

        $emploiDuTemps->update($data);

        return response()->json($emploiDuTemps);
    }

    public function destroy(EmploiDuTemps $emploiDuTemps)
    {
        $emploiDuTemps->delete();

        return response()->json(['message' => 'Emploi du temps supprimé.']);
    }
}
