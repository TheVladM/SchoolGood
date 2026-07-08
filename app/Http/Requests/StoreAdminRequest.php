<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:admins,username',
            'password' => 'required|string|min:8',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'actif' => 'boolean',
            'typeAdmin' => 'nullable|string|max:100',
            'mobile' => 'nullable|string|max:30',
            'alanyaID' => 'nullable|string|max:255',
        ];
    }
}
