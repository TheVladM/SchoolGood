<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreParentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'idPers' => 'required|exists:personnes,idPers',
            'matricule' => 'required|exists:eleves,matricule',
            'idAdmin' => 'nullable|exists:admins,ID',
            'idPers' => [
                'required',
                Rule::exists('personnes', 'idPers'),
            ],
            'matricule' => [
                'required',
                Rule::exists('eleves', 'matricule'),
                Rule::unique('parents')->where(function ($query) {
                    return $query->where('idPers', $this->input('idPers'));
                }),
            ],
        ];
    }
}
