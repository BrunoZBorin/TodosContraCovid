<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\UnidadeSaude;
use App\Http\Requests\UnidadeSaudeFormRequest;

class UnidadeSaudeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $unidadeSaude = UnidadeSaude::all();
        return response()->json($unidadeSaude, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UnidadeSaudeFormRequest $request)
    {
        if(isset($validator) && $validator->fails()){
            return response()->json($validator->messages(), 400);
        }else{
            $address = \Correios::cep($request->cep);
            $unidadeSaude = new UnidadeSaude();
            $unidadeSaude->fill($request->all());
            if(!empty($address)) {
                $unidadeSaude->logradouro = $address['logradouro'];
                $unidadeSaude->bairro = $address['bairro'];
                $unidadeSaude->cidade = $address['cidade'];
                $unidadeSaude->estado = $address['uf'];
                $unidadeSaude->save();
                return response()->json($unidadeSaude, 201);
            }else{
                $message = "O cep não existe";
                return response()->json($message, 400);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $unidadeSaude = UnidadeSaude::findOrFail($id);
        return response()->json($unidadeSaude, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UnidadeSaudeFormRequest $request, $id)
    {
        $unidadeSaude = UnidadeSaude::findOrFail($id);
        $endereco = \Correios::cep($request->cep);
        $unidadeSaude->fill($request->all());
        if(!empty($endereco)) {
            $unidadeSaude->logradouro = $endereco['logradouro'];
            $unidadeSaude->bairro = $endereco['bairro'];
            $unidadeSaude->cidade = $endereco['cidade'];
            $unidadeSaude->estado = $endereco['uf'];
        }
        $unidadeSaude->save();
        return response()->json($unidadeSaude, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $unidadeSaude = UnidadeSaude::findOrFail($id);
        $unidadeSaude->delete();
        return response()->json([], 200);
    }
}
