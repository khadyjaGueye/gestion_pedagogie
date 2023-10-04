<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourRequest extends FormRequest
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
            "nbreHeure" => 'required',
            // "classe_id" => 'required|exists:cours,id',
            // "module_id" => 'required|exists:cours,id',
            // "semestre_id" => 'required|exists:cours,id',
            // "prof_id" => 'required|exists:cours,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nbreHeure.required' => 'le nombre d\'heure doit etre requis',
            'module_prof_id.required' => "le module et le prof doit etre requis",
            'module_id.required' => "l'\annee doit etre requis",
            'semestre_id.required' => "le semestre doit etre requis",
            'prof_id.required' => "le prof doit etre requis",
        ];
    }
}
