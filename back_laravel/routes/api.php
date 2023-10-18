<?php

use App\Http\Controllers\AnneeSolaireController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\CourController;
use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
 Route::group(['middleware' => ['auth:sanctum']], function () {

//     Route::post('/cours', [CourController::class, 'store']);
//     Route::get("/cours", [CourController::class, 'index']);

//     Route::get('/module/{id}', [ModuleController::class, 'getProf']);

//     Route::get('/session', [SessionController::class, 'index']);
//     Route::post('/session', [SessionController::class, 'store']);

//     Route::get('/classe', [ClasseController::class, 'index']);
//     Route::post('/classe', [ClasseController::class, 'store']);
Route::get('/prof',[UserController::class,'nombreHeure']);
Route::post('/prof',[UserController::class,'demandeAnnulation']);
 });

 Route::get('/prof/{id}',[UserController::class,'listeCour']);

 Route::get('/etudiant',[EtudiantController::class,'index']);


Route::post('/cours', [CourController::class, 'store']);
Route::get("/cours", [CourController::class, 'index']);

Route::get('/module/{id}', [ModuleController::class, 'getProf']);

Route::get('/session', [SessionController::class, 'index']);
Route::post('/session', [SessionController::class, 'store']);

Route::get('/annee',[AnneeSolaireController::class,'annee_scolaire_en_cour']);

Route::get('/classe', [ClasseController::class, 'index']);
Route::post('/classe', [ClasseController::class, 'store']);

Route::post('/login', [UserController::class, 'login']);
Route::get('/logout/{id}', [UserController::class, 'logout']);

