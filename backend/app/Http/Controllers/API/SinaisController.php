<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Sinais;
use App\AtendimentoSinais;
use App\Http\Requests\SinaisFormRequest;

class SinaisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sinais = Sinais::all();
        return response()->json($sinais, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SinaisFormRequest $request)
    {
        $sinais = Sinais::create($request->all());
        $atendimento_sinais = new AtendimentoSinais();
        $atendimento_sinais->sinais_id = $sinais->id;
        $atendimento_sinais->atendimento_id = $request->atendimento_id;
        $atendimento_sinais->save();

        return response()->json([$sinais, $atendimento_sinais ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sinais = Sinais::findOrFail($id);
        return response()->json($sinais, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SinaisFormRequest $request, $id)
    {
        $sinais = Sinais::findOrFail($id);
        $sinais->fill($request->all());
        $sinais->save();
        return response()->json($sinais, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sinais = Sinais::findOrFail($id);
        $sinais->delete();
        return response()->json([], 200);
    }
}
