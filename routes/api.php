<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Rotas protegidas por autenticação
Route::prefix('v1')->middleware('jwt.auth')->group(function() {
//Rotas cliente no método apiResource
Route::apiResource('cliente', 'App\Http\Controllers\ClienteController');
//Rota Carro no apiResource
Route::apiResource('carro', 'App\Http\Controllers\CarroController');
//Rota Locação no apiResource
Route::apiResource('locacao', 'App\Http\Controllers\LocacaoController');
//Rota marca no apiResource
Route::apiResource('marca', 'App\Http\Controllers\MarcaController');
//Rota modelo no apiResource
Route::apiResource('modelo', 'App\Http\Controllers\ModeloController');
//Rota me
Route::post('me', 'App\Http\Controllers\AuthController@me');
//Rota refresh
Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
//Rota para logout
Route::post('logout', 'App\Http\Controllers\AuthController@logout');
});



//Rota para login
Route::post('login', 'App\Http\Controllers\AuthController@login');
