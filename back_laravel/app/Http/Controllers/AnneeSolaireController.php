<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use Illuminate\Http\Request;

class AnneeSolaireController extends Controller
{
    public function annee_scolaire_en_cour(){
        $annee_en_cour = AnneeScolaire::where("status",1)->first();
        return response()->json([
            "data" => [
                "annee"=>[$annee_en_cour]
            ]
        ]);
    }
}
