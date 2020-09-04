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
Route::get('/pacientes_export_excel/{id}', 'API\PacienteController@export_excel');
Route::get('/pacientes_export_pdf/{id}', 'API\PacienteController@export_pdf');
Route::get('/atendimentos_export_excel/{id}', 'API\AtendimentoController@export_excel');
Route::get('/atendimentos_export_pdf/{id}', 'API\AtendimentoController@export_pdf');
Route::get('/index_atendimentos/{id}', 'API\AtendimentoController@index_atendimentos');
//Graficos
Route::get('/atendimentos_por_data', 'API\AtendimentoController@atendimentos_por_data');
Route::get('/pacientes_obitos', 'API\PacienteController@obitos');
Route::get('/pacientes_idades', 'API\PacienteController@idades');
Route::get('/pacientes_com_comorbidades', 'API\PacienteController@pacientes_com_comorbidades');
Route::get('/casos_finalizados', 'API\PacienteController@casos_finalizados');
Route::get('/casos_positivos', 'API\PacienteController@casos_positivos');
//Listagem diaria
Route::get('/listagem_diaria/{id}', 'API\AtendimentoController@listagem_diaria');

Route::get('/show_sinais_familiares/{id}', 'API\PacienteController@show_sinais_familiares');
Route::get('/show_atendimento_sinais/{id}', 'API\AtendimentoController@show_atendimento_sinais');
Route::post('/atendimento_create_paciente_update', 'API\AtendimentoController@store_atendimento_update_paciente');
Route::post('/usuario_is_admin/{id}', 'API\UserController@is_admin');
Route::post('/usuario_is_not_admin/{id}', 'API\UserController@is_not_admin');