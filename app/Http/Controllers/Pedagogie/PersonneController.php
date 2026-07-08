<?php

namespace App\Http\Controllers\Pedagogie;

use App\Http\Controllers\Controller;
use App\Http\Traits\ReusablePhotoUpload;
use App\Models\Admin;
use App\Models\Personne;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class PersonneController extends Controller
{
    use ReusablePhotoUpload;

    public function index()
    {
        return response()->json(Personne::with(['admin', 'titulaires', 'enseignants', 'epreuves'])->paginate(20));
    }

    public function create()
    {
        return response()->json(['admins' => Admin::all()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'dateNaissance' => 'nullable|date',
            'lieuNaissance' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:30',
            'phone' => 'nullable|string|max:30',
            'typePersonne' => 'required|string|max:100',
            'username' => 'required|string|max:255|unique:personnes,username',
            'password' => 'required|string|min:8',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'alanyaID' => 'nullable|string|max:255',
            'idAdmin' => 'nullable|exists:admins,ID',
        ]);

        $photoURL = $this->uploadPhoto($request, 'photo', new Personne(), 'photoURL', 'photos/personnes');
        $data = Arr::except($data, ['photo']);
        $data['password'] = Hash::make($data['password']);

        if ($photoURL !== null) {
            $data['photoURL'] = $photoURL;
        }

        $personne = Personne::create($data);

        return response()->json($personne, 201);
    }

    public function show(Personne $personne)
    {
        $personne->load(['admin', 'titulaires', 'enseignants', 'epreuves', 'sessions', 'evaluations']);

        return response()->json($personne);
    }

    public function edit(Personne $personne)
    {
        return response()->json([
            'personne' => $personne,
            'admins' => Admin::all(),
        ]);
    }

    public function update(Request $request, Personne $personne)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'dateNaissance' => 'nullable|date',
            'lieuNaissance' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:30',
            'phone' => 'nullable|string|max:30',
            'typePersonne' => 'required|string|max:100',
            'username' => 'required|string|max:255|unique:personnes,username,' . $personne->idPers . ',idPers',
            'password' => 'nullable|string|min:8',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'alanyaID' => 'nullable|string|max:255',
            'idAdmin' => 'nullable|exists:admins,ID',
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $photoURL = $this->uploadPhoto($request, 'photo', $personne, 'photoURL', 'photos/personnes');

        $data = Arr::except($data, ['photo']);

        if ($photoURL !== null) {
            $data['photoURL'] = $photoURL;
        }

        $personne->update($data);

        return response()->json($personne);
    }

    public function search(Request $request)
    {
        $q = trim($request->query('q', ''));
        $type = $request->query('type', 'parent');

        if ($q === '') {
            return response()->json([]);
        }

        $typePersonne = $type === 'parent' ? '2' : $type;

        $personnes = Personne::where('typePersonne', $typePersonne)
            ->where(function ($query) use ($q) {
                $query->where('nom', 'like', "%{$q}%")
                    ->orWhere('prenom', 'like', "%{$q}%");
            })
            ->limit(10)
            ->get(['idPers', 'nom', 'prenom', 'mobile', 'alanyaID']);

        return response()->json($personnes);
    }

    public function destroy(Personne $personne)
    {
        $personne->delete();

        return response()->json(['message' => 'Personne supprimée.']);
    }
}
