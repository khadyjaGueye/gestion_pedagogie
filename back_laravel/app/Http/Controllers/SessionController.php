<?php

namespace App\Http\Controllers;

use App\Http\Resources\SessionResource;
use App\Models\Classe;
use App\Models\Cour;
use App\Models\ModuleProf;
use App\Models\Salle;
use App\Models\Semestre;
use App\Models\Session;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use function Laravel\Prompts\error;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sessions = Session::all();
        $semestres = Semestre::all();
        return response()->json([
            "data" => [
                "sessions" => SessionResource::collection($sessions),
                "semestres" => $semestres
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
    public function store(Request $request)
    {
        $module_prof = ModuleProf::where("prof_id", $request->prof_id)
            ->where("module_id", $request->module_id)->first()->id;
        $cours = Cour::where("module_prof_id", $module_prof)->first();
         //return $cours;
        if ($request->classe_id !=  $cours->classe_id) {
            return Response::HTTP_NOT_FOUND;
        }

        $totalDuration = Session::sum('duree');
        if ($totalDuration > $cours->nbreHeure) {
            return Response::HTTP_NOT_FOUND;
        }
        if ($request->etat == true) {
            $salle = Salle::find($request->salle_id);
            // return $salle;
            if (!$salle->isAvailable($request->dateCours, $request->hDedut, $request->hFin)) {
                // return error();
            }
        }
        $prof = User::find($request->prof_id);
        if (!$prof->isAvailable($request->dateCours, $request->hDedut, $request->hFin)) {
            // return error();
        }
        $data = [
            'dateCours' => $request->dateCours,
            'hDedut' => $request->hDedut,
            'salle_id'=>$request->salle_id,
            'hFin' => $request->hFin,
            "duree" => $request->hFin - $request->hDedut,
            'etat' => $request->etat,
            "cour_id" => $cours->id,
        ];
        $session = new Session();
        $session->fill($data);
        $session->save();

        return response()->json([
            "data" => [
                "session" => new SessionResource($session)
            ]
        ]);
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
