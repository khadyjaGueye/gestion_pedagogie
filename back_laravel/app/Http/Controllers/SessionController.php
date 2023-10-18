<?php

namespace App\Http\Controllers;

use App\Http\Resources\SessionResource;
use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Cour;
use App\Models\ModuleProf;
use App\Models\Salle;
use App\Models\Semestre;
use App\Models\Session;
use App\Models\User;
use Carbon\Carbon;
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
        // Récupérer les valeurs de la requête
        $heureDebut = $request->input('hDedut');
        $heureFin = $request->input('hFin');
        $date = $request->input('date');
        /// Convertir en secondes
        $debutSeconds = strtotime($heureDebut);
        $finSeconds = strtotime($heureFin);
        // Calculer la durée en soustrayant les secondes
        $dureeEnSecondes = $finSeconds - $debutSeconds;
        // Conversion en heures/minutes/secondes
        $heures = floor($dureeEnSecondes / 3600);
        $reste = $dureeEnSecondes % 3600;
        $minutes = floor($reste / 60);
        $secondes = $reste % 60;
        $duree =  $heures . ':' . $minutes . ':' . $secondes;
        // if ($debutSeconds > $heureFin) {
        //    return response()->json(["message"=>" Heur de debut doit inferieure a heur de fin"]);
        // }
        if (!$this->isProfesseurDisponible($request->classe_id, $date, $heureDebut, $heureFin)) {
            return response()->json(['message' => 'Le professeur n\'est pas disponible à ces heures.'], 400);
        }
        $totalDuration = Session::sum('duree');
        //return $totalDuration;
        // if ($totalDuration < $cours->nbreHeure) {
        //     return response()->json(['message' => 'Le nombre heure est epuissee']);
        // }
        if ($request->etat == true) {
            $salle = Salle::find($request->salle_id);
           // return $salle->id;
            // Logique pour vérifier la disponibilité de la salle ici
            // Si la salle n'est pas disponible, renvoyer une réponse d'erreur
            if (!$this->isSalleDisponible($salle->id, $date, $heureDebut, $heureFin)) {
                return response()->json(['message' => 'La salle n\'est pas disponible à ces heures.'], 400);
            }

            // if (!$salle->isAvailable($request->dateCours, $request->hDedut, $request->hFin)) {
            //     return response()->json(['message' => 'Le salle n\'est pas disponible ']);
            // }
        }
        // $prof = User::find($request->prof_id);
        // if (!$prof->isAvailable($request->dateCours, $request->hDedut, $request->hFin)) {
        //     return response()->json(['message' => 'Le prof n\'est pas disponible ']);
        // }
        $data = [
            'dateCours' => $request->dateCours,
            'hDedut' => $heureDebut,
            'salle_id' => $request->salle_id,
            'hFin' =>  $heureFin,
            "duree" =>  $duree,
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
    public function isSalleDisponible($salleId, $date, $heureDebut, $heureFin)
    {
        $sessions = Session::where('salle_id', $salleId)
            ->where('dateCours', $date)
            ->get();
        // dd($sessions);
        foreach ($sessions as $session) {
            if (($heureDebut >= $session->hDedut && $heureDebut < $session->hFin) ||
                ($heureFin > $session->hDedut && $heureFin <= $session->hFin) ||
                ($heureDebut <= $session->hDedut && $heureFin >= $session->hFin)
            ) {
                return false;
            }
        }

        return true;
    }
    function isProfesseurDisponible($classId, $date, $startHour, $endHour) {

        // Récupérer l'id du module associé à la classe
        $course = Cour::where("classe_id",$classId)->first();
       //dd($course);
       // $moduleId = $course->module_prof_id;
        // Récupérer les sessions de ce module à la date donnée
        $sessions = Session::where('cour_id', $course)
                           ->where('dateCours', $date)
                           ->get();

        // Boucler sur les sessions
        foreach($sessions as $session) {

          // Vérifier si la plage horaire chevauche
          if($startHour < $session->endHour && $endHour > $session->startHour) {
            return false;
          }

        }
        // Aucun chevauchement trouvé, le prof est disponible
        return true;

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
