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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::apiResources([
    'unidades_saude' => 'API\UnidadeSaudeController',
    'unidades_sintomaticas' => 'API\UnidadeSintomaticaController',
    'sinais' => 'API\SinaisController',
    'atendimentos' => 'API\AtendimentoController',
    'pacientes' => 'API\PacienteController',
    'comorbidades' => 'API\ComorbidadesController',
    'familiares' => 'API\FamiliarController',
    'users' => 'API\UserController'
    ]);
Route::post('/primeiro_cadastro', 'API\PacienteController@primeiro_cadastro');
Route::get('/cep', 'API\PacienteController@cep');
Route::post('/register', 'API\AuthController@register');
Route::post('/login', 'API\AuthController@login');
Route::get('/pacientes_export_excel', 'API\PacienteController@export_excel');
Route::get('/pacientes_export_pdf', 'API\PacienteController@export_pdf');
Route::get('/atendimentos_export_excel', 'API\AtendimentoController@export_excel');
Route::get('/atendimentos_export_pdf', 'API\AtendimentoController@export_pdf');
Route::get('/show_sinais_familiares/{id}', 'API\PacienteController@show_sinais_familiares');
Route::get('/show_atendimento_sinais/{id}', 'API\AtendimentoController@show_atendimento_sinais');
