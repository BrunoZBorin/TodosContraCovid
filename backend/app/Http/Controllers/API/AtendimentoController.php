<?php

namespace App\Http\Controllers\API;

use App\Atendimento;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AtendimentoFormRequest;

class AtendimentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $atendimento = Atendimento::all();
        return response()->json($atendimento, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AtendimentoFormRequest $request)
    {
        $atendimento = Atendimento::create($request->all());
        return response()->json($atendimento, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $atendimento = Atendimento::findOrFail($id);
        return response()->json($atendimento, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AtendimentoFormRequest $request, $id)
    {
        $atendimento = Atendimento::findOrFail($id);
        $atendimento->fill($request->all());
        $atendimento->save();
        return response()->json($atendimento, 200);
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $atendimento = Atendimento::findOrFail($id);
        $atendimento->delete();
        return response()->json([], 200);
    }
}
