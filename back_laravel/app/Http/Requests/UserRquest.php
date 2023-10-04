<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRquest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
        "nom"=>'required',
        "prenom"=>"require",
        "email"=>"required|unique:users|email",
        "role"=>"required",
        "password"=>"required"
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'le nom doit etre requis',
            'prenom.required' => "le prenom doit etre requis",
            'email.email' => "le format de l'email est invalide",
            'email.unique' => "l'email existe déjà",
            'password.required' => 'le password doit etre requis',
            'role' => 'le role doit etre requis',
        ];
    }
}
