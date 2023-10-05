<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourRequest;
use App\Http\Resources\CourResource;
use App\Models\Classe;
use App\Models\Cour;
use App\Models\CourClasse;
use App\Models\Module;
use App\Models\ModuleProf;
use App\Models\Semestre;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = Classe::all();
        // $profs = User::where('role', 'prof')->get();
        $semestres = Semestre::all();
        $modules = Module::with('profs')->get();
        $cours = Cour::all();
        return response()->json([
            "data" => [
                "modules" => $modules,
                "classes" => $classes,
                //"profs" => $profs,
                "semestres" => $semestres,
               // "cours"=>$cours
                "cours" => CourResource::collection($cours),
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourRequest $request)
    {
        try {

            $prof = User::where('role', 'prof')
                ->where('id', $request->prof_id)->first();
            //return $prof;
            $module = Module::where('id', $request->module_id)->first();
            $prof_module =  ModuleProf::where('prof_id', $prof->id)
                ->where('module_id', $module->id)->first();
            if ($prof_module) {
                $cours = Cour::create([
                    "nbreHeure" => $request->nbreHeure,
                    "semestre_id" => $request->semestre_id,
                    "annee_scolaire_id" => 1,
                    "module_prof_id" => $prof_module->id,
                    "classe_id" => $request->classe_id
                ]);
            }else{
                return response()->json([
                    "message" => "le prof n'existe pas"
                ]);
            }

            return response()->json(
                ["data" => [
                    "cours" => $cours
                ]]
            );
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
