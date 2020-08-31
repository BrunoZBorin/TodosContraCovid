<?php

namespace App\Http\Controllers\API;

use App\Comorbidades;
use App\PacienteComorbidades;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ComorbidadesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comorbidades = Comorbidades::all();
        return response()->json($comorbidades, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $comorbidades = Comorbidades::create($request->all());
        $paciente_comorbidades = new PacienteComorbidades();
        $paciente_comorbidades->paciente_id = $request->paciente_id;
        $paciente_comorbidades->comorbidades_id = $comorbidades->id;
        $paciente_comorbidades->save();
        return response()->json([$comorbidades, $paciente_comorbidades], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $comorbidade = Comorbidades::findOrFail($id);
        return response()->json($comorbidade, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $comorbidade = Comorbidades::findOrFail($id);
        $comorbidade->fill($request->all());
        $comorbidade->save();
        return response()->json($comorbidade, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comorbidade = Comorbidades::findOrFail($id);
        $comorbidade->delete();
        return response()->json([], 200);
    }
}
