<?php

namespace App\Http\Controllers\Administration;

use App\Exports\AdminsExport;
use App\Exports\ElevesExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Http\Traits\ReusablePhotoUpload;
use App\Models\Admin;
use App\Models\Eleve;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;

class AdminController extends Controller
{
    use ReusablePhotoUpload;

    public function index(Request $request)
    {
        return response()->json(Admin::when($request->query('typeAdmin'), function ($query, $typeAdmin): void {
            $query->where('typeAdmin', $typeAdmin);
        })->paginate(20));
    }

    public function create()
    {
        return response()->json([]);
    }

    public function store(StoreAdminRequest $request)
    {
        $data = $request->validated();
        $photoURL = $this->uploadPhoto($request, 'photo', new Admin(), 'photoURL', 'photos/admins');

        $data = Arr::except($data, ['photo']);
        $data['password'] = Hash::make($data['password']);

        if ($photoURL !== null) {
            $data['photoURL'] = $photoURL;
        }

        $admin = Admin::create($data);

        return response()->json($admin, 201);
    }

    public function show(Admin $admin)
    {
        return response()->json($admin);
    }

    public function edit(Admin $admin)
    {
        return response()->json(['admin' => $admin]);
    }

    public function update(UpdateAdminRequest $request, Admin $admin)
    {
        $data = $request->validated();
        $photoURL = $this->uploadPhoto($request, 'photo', $admin, 'photoURL', 'photos/admins');

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $data = Arr::except($data, ['photo']);

        if ($photoURL !== null) {
            $data['photoURL'] = $photoURL;
        }

        $admin->update($data);

        return response()->json($admin);
    }

    public function destroy(Admin $admin)
    {
        $this->deleteStoredPhoto($admin, 'photoURL', 'public');
        $admin->delete();

        return response()->json(['message' => 'Administrateur supprimé.']);
    }

    public function listePdf(Request $request)
    {
        $type = $request->query('type');
        $logo = url(trim(env('SCHOOL_LOGO_PATH', 'images/logo.png'), '/'));
        $date = now()->format('d/m/Y');

        if ($type === 'admins') {
            $admins = Admin::when($request->query('typeAdmin'), function ($query, $typeAdmin): void {
                $query->where('typeAdmin', $typeAdmin);
            })
            ->when($request->query('actif') !== null, function ($query) use ($request): void {
                if ($request->query('actif') !== '') {
                    $query->where('actif', (bool) $request->query('actif'));
                }
            })
            ->orderBy('nom')
            ->get();

            return Pdf::loadView('admin.liste-admins', [
                'logo' => $logo,
                'date' => $date,
                'admins' => $admins,
                'total' => $admins->count(),
            ])->setPaper('a4', 'portrait')->stream('liste-admins.pdf');
        }

        if ($type === 'eleves') {
            $eleves = Eleve::with(['frequentes.salle.classe'])
                ->when($request->query('annee'), function ($query, $annee): void {
                    $query->whereHas('frequentes', function ($subQuery) use ($annee) {
                        $subQuery->where('idAcademi', $annee);
                    });
                })
                ->when($request->query('classe'), function ($query, $classe): void {
                    $query->whereHas('frequentes.salle', function ($subQuery) use ($classe) {
                        $subQuery->where('idClasse', $classe);
                    });
                })
                ->when($request->query('actif') !== null, function ($query) use ($request): void {
                    if ($request->query('actif') !== '') {
                        $query->where('actif', (bool) $request->query('actif'));
                    }
                })
                ->get()
                ->sortBy(function (Eleve $eleve) {
                    return optional($eleve->frequentes->first()?->salle?->classe)->libelle . '|' . $eleve->nom;
                });

            return Pdf::loadView('admin.liste-eleves', [
                'logo' => $logo,
                'date' => $date,
                'eleves' => $eleves,
                'total' => $eleves->count(),
            ])->setPaper('a4', 'landscape')->stream('liste-eleves.pdf');
        }

        return response()->json(['message' => 'Paramètre type invalide.'], 400);
    }

    public function listeExcel(Request $request)
    {
        $type = $request->query('type');

        if ($type === 'admins') {
            $filename = 'liste-admins.xlsx';
            return Excel::download(new AdminsExport($request->query('typeAdmin'), $request->query('actif')), $filename);
        }

        if ($type === 'eleves') {
            $filename = 'liste-eleves.xlsx';
            return Excel::download(new ElevesExport($request->query('classe'), $request->query('annee'), $request->query('actif')), $filename);
        }

        return response()->json(['message' => 'Paramètre type invalide.'], 400);
    }
}
