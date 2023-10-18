<?php

namespace App\Http\Controllers;

use App\Http\Resources\SessionResource;
use App\Http\Resources\UserResource;
use App\Models\Cour;
use App\Models\Demande;
use App\Models\ModuleProf;
use App\Models\Session;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function logout($id)
    {
        $user = User::find($id);
        Auth::logout($user);
        return response()->json([
            'status' => 'success',
            'message' => 'Deconnection reuisie  '
        ]);
    }
    public function login(Request $request)
    {
        try {
            $validateUser = validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]
            );
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error: ',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'email ou password incorrect'
                ], 401);
            }
            $user = User::where('email', $request->email)->first();
            //$user = Auth::user(); c'est une autre facon de recupere l'utilisateur
            // $token = $user->createToken("API Token")->plainTextToken;
            // $cookie = cookie("token",$token,24*60);

            return response()->json([
                'user' => new UserResource($user),
                'token' => $user->createToken("API Token")->plainTextToken,
                'status' => true
            ], 200);
        } catch (\Throwable $th) {

            return response()->json([
                'status' => false,
                'message' => $th
            ], 500);
        }
    }
    public function listeCour($id){
        $prof_id = $id;
        $module_prof = ModuleProf::where("prof_id",$prof_id)->first();
        $cour = Cour::where("module_prof_id",$module_prof->id)->first();
        //return $cour->id;
        $session = Session::where("cour_id",$cour->id)->get();
        //return $session;
        return response()->json([
            "data"=>[
                "cours"=>SessionResource::collection($session)
            ]
        ]);
    }
    public function nombreHeure()
    {
        $user = Auth::user();
        if ($user->role == 'prof') {
            $prof_id = $user->id;
            $module_id = ModuleProf::where("prof_id", $prof_id)->first();
            //  return $module_id->id;
            $totalHeures = 0;
            $cours = Cour::where('module_prof_id', $module_id->id)->get();
            foreach ($cours as $cour) {
                $sessions = Session::where("cour_id", $cour->id)
                    ->whereMonth('dateCours', '=', now()->month)
                    ->whereYear('dateCours', '=', now()->year)
                    ->where('etat', '!=', 0)
                    ->get();
                // return $session;
            }
            foreach ($sessions as $session) {
                $heureDebut = Carbon::parse($session->hDedut);
                $heureFin = Carbon::parse($session->hFin);
                $dureeEnMinutes = $heureFin->diffInMinutes($heureDebut);
                $totalHeures += $dureeEnMinutes / 60;
            }
            return response()->json($totalHeures);
        }
    }
    public function demandeAnnulation(Request $request)
    {
        $user = Auth::user();
        if ($user->role == 'prof') {
            $session_id = $request->session_id;
            $session = Session::find($session_id);
            if (!$session || $session->etat === 0) {
                return response()->json(['message' => 'Cette session est déjà annulée.']);
            }
            // if (!$session || $session->etat === 1) {
            //     return response()->json(['message' => 'Cette session est déjà valider.']);
            // }
            $demande = Demande::create([
                'prof_id' => $user->id,
                'session_id' => $session_id,
                'motif' => $request->input('motif'),
                'etat_demande' => 'attente',
            ]);
            return response()->json(['message' => 'Demande annulation soumise avec succès']);
        }
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
