<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourRequest;
use App\Models\Cour;
use App\Models\CourClasse;
use App\Models\Module;
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
        //
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
            DB::beginTransaction();

            $prof = User::where('role','prof')->where('id',$request->prof_id)->first();
            $module = Module::where('id',$request->module_id)->first();
            return $module;
            $cours = Cour::create([
                "nbreHeure" => $request->nbreHeure,
                "semestre_id"=>$request->semestre_id,
                "annee_scolaire_id"=>$request->annee_scolaire_id,
                "module_prof_id"=>$request->module_prof_id
            ]);

            $classeCour = new CourClasse;
            $classeCour->cour_id = $cours->id;
            $classeCour->classe_id = $request->classe_id;
            $classeCour->save();
            DB::commit();
            return response()->json(
                ["data" => [
                    "cours" => $cours,
                    "classeCour" => $classeCour
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
