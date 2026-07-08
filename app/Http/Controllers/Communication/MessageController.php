<?php

namespace App\Http\Controllers\Communication;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AnneeAcademique;
use App\Models\Message;
use App\Models\ParentEleve;
use App\Models\Personne;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        return response()->json(Message::with(['expéditeur', 'parent', 'anneeAcademique', 'admin'])->paginate(20));
    }

    public function create()
    {
        return response()->json([
            'personnes' => Personne::all(),
            'parents' => ParentEleve::all(),
            'annees' => AnneeAcademique::all(),
            'admins' => Admin::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'idExp_Pers' => 'required|exists:personnes,idPers',
            'idParent' => 'nullable|exists:parents,idParent',
            'objet' => 'required|string|max:255',
            'information' => 'required|string',
            'type_message' => 'nullable|string|max:100',
            'AnneeAcade' => 'nullable|exists:annee_academiques,idAnnee',
            'valider' => 'boolean',
            'idAdmin' => 'nullable|exists:admins,ID',
        ]);

        $message = Message::create($data);

        return response()->json($message, 201);
    }

    public function show(Message $message)
    {
        $message->load(['expéditeur', 'parent', 'anneeAcademique', 'admin']);

        return response()->json($message);
    }

    public function edit(Message $message)
    {
        return response()->json([
            'message' => $message,
            'personnes' => Personne::all(),
            'parents' => ParentEleve::all(),
            'annees' => AnneeAcademique::all(),
            'admins' => Admin::all(),
        ]);
    }

    public function update(Request $request, Message $message)
    {
        $data = $request->validate([
            'idExp_Pers' => 'required|exists:personnes,idPers',
            'idParent' => 'nullable|exists:parents,idParent',
            'objet' => 'required|string|max:255',
            'information' => 'required|string',
            'type_message' => 'nullable|string|max:100',
            'AnneeAcade' => 'nullable|exists:annee_academiques,idAnnee',
            'valider' => 'boolean',
            'idAdmin' => 'nullable|exists:admins,ID',
        ]);

        $message->update($data);

        return response()->json($message);
    }

    public function destroy(Message $message)
    {
        $message->delete();

        return response()->json(['message' => 'Message supprimé.']);
    }
}
