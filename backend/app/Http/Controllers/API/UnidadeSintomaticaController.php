<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\UnidadeSintomatica;

class UnidadeSintomaticaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $unidadesSintomaticas = UnidadeSintomatica::all();
        return response()->json($unidadesSintomaticas, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $endereco = \Correios::cep($request->cep);
        $unidadeSintomatica = new UnidadeSintomatica();
        $unidadeSintomatica->fill($request->all());
        if(!empty($endereco)) {
            $unidadeSintomatica->logradouro = $endereco['logradouro'];
            $unidadeSintomatica->bairro = $endereco['bairro'];
            $unidadeSintomatica->cidade = $endereco['cidade'];
            $unidadeSintomatica->estado = $endereco['uf'];
        }
        $unidadeSintomatica->save();
        return response()->json($unidadeSintomatica, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $unidadeSintomatica = UnidadeSintomatica::findOrFail($id);
        return response()->json($unidadeSintomatica, 200);
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
        $unidadeSintomatica = UnidadeSintomatica::findOrFail($id);
        $endereco = \Correios::cep($request->cep);
        $unidadeSintomatica->fill($request->all());
        if(!empty($endereco)) {
            $unidadeSintomatica->logradouro = $endereco['logradouro'];
            $unidadeSintomatica->bairro = $endereco['bairro'];
            $unidadeSintomatica->cidade = $endereco['cidade'];
            $unidadeSintomatica->estado = $endereco['uf'];
        }
        $unidadeSintomatica->save();
        return response()->json($unidadeSintomatica, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $unidadeSintomatica = UnidadeSintomatica::findOrFail($id);
        $unidadeSintomatica->delete();
        return response()->json([],200);
    }
}
