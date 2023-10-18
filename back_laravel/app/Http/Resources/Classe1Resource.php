<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Classe1Resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "nom"=>$this->nom,
            "effectifs"=>$this->effectifs,
            "filiere"=> new FiliereResource($this->filiere),
            "niveau"=>new NiveauResource($this->niveau),
        ];
    }
}
