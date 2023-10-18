<?php

namespace App\Http\Controllers;

use App\Http\Resources\Classe1Resource;
use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Inscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClasseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = Classe::all();
        return response()->json([
            "data" => [
                "classes" => Classe1Resource::collection($classes)
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
        $annee = AnneeScolaire::where('status', '1')->first();
        //return $annee;
        if ($annee) {
            $idAnnee = $annee->id;
            $etudiants = $request->input('doc');
            DB::beginTransaction();
            try {
                foreach ($etudiants as $etudiant) {
                    $apprenant = User::where('email', $etudiant['email'])
                        ->where('role', 'etudiant')
                        ->first();
                   // dd($apprenant);
                    if ($apprenant == null) {
                        //  dd($etudiants);
                        $newApprenant = User::create([
                            "nom" => $etudiant['nom'],
                            "prenom" => $etudiant['prenom'],
                            "role" => $etudiant['role'],
                            "email" => $etudiant['email'],
                            "password" => bcrypt($etudiant['password']),
                        ]);
                        // dd($newApprenant->id);
                        $idClasse = Classe::where('nom', $etudiant['classe'])->first()->id;
                        // dd($idClasse);
                        Inscription::create([
                            "classe_id" => $idClasse,
                            "etudiant_id" => $newApprenant->id,
                            "annee_scolaire_id" => $idAnnee
                        ]);
                    } else {
                        $idClasse = Classe::where('nom', $etudiant['classe'])->first()->id;
                        Inscription::create([
                            "classe_id" => $idClasse,
                            "etudiant_id" => $apprenant->id,
                            "annee_scolaire_id" => $idAnnee,
                        ]);
                    }
                }
                DB::commit();
                return response(['message' => 'Inscription réussie']);
            } catch (\Exception $e) {
                DB::rollback();
                // Log::error($e);
                return response(['message' => $e->getMessage()], 500);
            }
            // Le reste de votre code ici...
        } else {
            // Gérer le cas où aucune année avec le statut 1 n'est trouvée
            return response(['message' => 'Aucune année avec le statut 1 trouvée.'], 404);
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
