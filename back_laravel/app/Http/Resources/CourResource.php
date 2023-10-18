<?php

namespace App\Http\Resources;

use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourResource extends JsonResource
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
            "nbreHeure"=>$this->nbreHeure,
            "etat"=>$this->etat,
            "prof"=>$this->module_prof->prof,
            "module"=>$this->module_prof->module,
            "classe"=>new ClasseResource($this->classe),
            "semestre"=>new SemestreResource($this->semestre),
        ];
    }
}
