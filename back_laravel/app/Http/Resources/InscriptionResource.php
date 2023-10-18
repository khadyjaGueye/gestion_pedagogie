<?php

namespace App\Http\Resources;

use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "etudiant"=>new UserResource($this->etudiant),
            "classe"=>new ClasseResource($this->classe)
        ];
    }
}
