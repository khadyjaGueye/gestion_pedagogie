<?php

use App\Http\Controllers\CourController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\SessionController;
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

Route::post('/cours',[CourController::class,'store']);
Route::get("/cours",[CourController::class,'index']);


Route::get('/module/{id}',[ModuleController::class,'getProf']);

Route::get('/session',[SessionController::class,'index']);
Route::post('/session',[SessionController::class,'store']);
