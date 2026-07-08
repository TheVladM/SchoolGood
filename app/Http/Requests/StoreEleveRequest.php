<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEleveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'matricule' => 'required|string|unique:eleves,matricule',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'dateNaissance' => 'nullable|date',
            'lieuNaissance' => 'nullable|string|max:255',
            'sexe' => 'nullable|in:M,F,Autre',
            'langue' => 'nullable|string|max:100',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'actif' => 'boolean',
            'idVilleNaissance' => 'nullable|exists:ville_naissances,idVille',
            'idAdmin' => 'nullable|exists:admins,ID',
        ];
    }
}
