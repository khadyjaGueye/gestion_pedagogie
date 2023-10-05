<?php

namespace App\Http\Controllers;

use App\Http\Resources\SessionResource;
use App\Models\Cour;
use App\Models\Salle;
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
        return response()->json([
            "data" => [
                "sessions" => SessionResource::collection($sessions)
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
        $nbreHeureCoursTotal = Cour::find($request->cour_id)->nbreHeure;
        //return $request->hDedut;
        //$sessions = Session::all();
        // $totalDuration = 0;
        // foreach ($sessions as $session) {
        //     $totalDuration += $session->duree;
        // }
        $totalDuration = Session::sum('duree');
        if ($totalDuration > $nbreHeureCoursTotal) {
           //  return Response::;
        }
        if ($request->etat == true) {
            $salle = Salle::find($request->salle_id);
           // return $salle;
            if (!$salle->isAvailable($request->dateCours, $request->hDedut, $request->hFin)) {
               // return error();
            }
        }
        $prof = User::find($request->prof_id);
        if(!$prof->isAvailable($request->dateCours, $request->hDedut, $request->hFin)) {
           // return error();
          }
          $session = Session::create($request->all());
          return response()->json([
            "data"=>[
                "session"=> new SessionResource($session)
            ]
          ]);

        // $session = Session::create([
        //     "dateCours" => $request->dateCours,
        //     "hDedut" => $request->hDedut,
        //     "hFin" => $request->hFin,
        //     "duree" => $request->hFin - $request->hDedut,
        //     "etat" => $request->etat,
        //     "salle_id" => $request->salle_id,
        //     "cour_id" => $request->cour_id,
        //     "prof_id" => $request->prof_id
        // ]);
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
