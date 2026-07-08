<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateParentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $parentId = $this->route('parent')?->idParent;

        return [
            'idPers' => 'required|exists:personnes,idPers',
            'matricule' => [
                'required',
                Rule::exists('eleves', 'matricule'),
                Rule::unique('parents')->where(function ($query) {
                    return $query->where('idPers', $this->input('idPers'));
                })->ignore($parentId, 'idParent'),
            ],
            'idAdmin' => 'nullable|exists:admins,ID',
        ];
    }
}
