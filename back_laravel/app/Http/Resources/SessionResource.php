<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "dateCours" => $this->dateCours,
            "hDedut" => $this->hDedut,
            "hFin" => $this->hFin,
            "duree" => $this->duree,
            "etat" => $this->etat,
            "salle" => new SalleResource($this->salle),
            "cour" => new CourResource($this->cour),
            //"prof" => new ProfResource($this->user),
        ];
    }
}
