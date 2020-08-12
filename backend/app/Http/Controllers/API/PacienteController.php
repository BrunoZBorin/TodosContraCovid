<?php

namespace App\Http\Controllers\API;

use App\Atendimento;
use App\AtendimentoSinais;
use App\Familiar;
use App\Http\Controllers\Controller;
use App\Paciente;
use App\PacienteComorbidades;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paciente = Paciente::all();
        return response()->json($paciente, 200);
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
        $paciente = new Paciente();
        $paciente->fill($request->all());
        if(!empty($endereco)) {
            $paciente->logradouro = $endereco['logradouro'];
            $paciente->bairro = $endereco['bairro'];
            $paciente->cidade = $endereco['cidade'];
            $paciente->estado = $endereco['uf'];
        }
        $paciente->save();
        return response()->json($paciente, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $paciente = Paciente::findOrFail($id);
        return response()->json($paciente, 200);
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
        $paciente = Paciente::findOrFail($id);
        $paciente->fill($request->all());
        $paciente->save();
        return response()->json($paciente, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $paciente = Paciente::findOrFail($id);
        $paciente->delete();
        return response()->json([], 200);
    }

    public function primeiro_cadastro(Request $request)
    {
        $endereco = \Correios::cep($request->paciente_cep);
        $paciente = new Paciente();
        if(!empty($endereco)) {
            $paciente->logradouro = $endereco['logradouro'];
            $paciente->bairro = $endereco['bairro'];
            $paciente->cidade = $endereco['cidade'];
            $paciente->estado = $endereco['uf'];
        }
        $paciente->nome = $request->paciente_nome;
        $paciente->cep = $request->paciente_cep;
        $paciente->numero = $request->paciente_numero;
        $paciente->telefone = $request->paciente_telefone;
        $paciente->cns = $request->paciente_cns;
        $paciente->data_nasc = $request->paciente_data_nasc;
        $paciente->obito = $request->paciente_obito;
        $paciente->primeira_avaliacao_medica = $request->paciente_primeira_avaliacao_medica;
        $paciente->isolamento_ate = $request->paciente_isolamento_ate;
        $paciente->data_inicio_sintomas = $request->paciente_data_inicio_sintomas;
        $paciente->data_coleta_exames = $request->paciente_data_coleta_exames;
        $paciente->unidade_sintomatica_id = $request->paciente_unidade_sintomatica_id;
        $paciente->convenio = $request->paciente_convenio;
        $paciente->unidade_saude_id = $request->paciente_unidade_saude_id;
        $paciente->tipo_exame = $request->paciente_tipo_exame;
        $paciente->data_resultado = $request->paciente_data_resultado;
        $paciente->resultado_exame = $request->paciente_resultado_exame;
        $paciente->grupo_risco = $request->paciente_grupo_risco;     

        $paciente->save();
        
        $atendimento = new Atendimento();
        $atendimento->data_hora_ligacao = $request->atendimento_data_hora_ligacao;
        $atendimento->isolamento = $request->atendimento_isolamento;
        $atendimento->orientacao = $request->atendimento_orientacao;
        $atendimento->apetite = $request->atendimento_apetite;
        $atendimento->febre = $request->atendimento_febre;
        $atendimento->tosse = $request->atendimento_tosse;
        $atendimento->falta_de_ar = $request->atendimento_falta_de_ar;
        $atendimento->observacoes_gerais = $request->atendimento_observacoes_gerais;
        $atendimento->orientacao_conduta = $request->atendimento_orientacao_conduta;
        $atendimento->paciente_id = $request->atendimento_paciente_id;
        $atendimento->usuario_id = $request->atendimento_usuario_id;
        
        $atendimento->save();
        
        $familiares = $request->familiares;
        $familiars = [];
        foreach($familiares as $familia)
        {
            $familiars[]=Familiar::create([
                'nome' => $familia['nome'],
                'sintomatico' => $familia['sintomatico'],
                'exame' => $familia['exame'],
                'paciente_id' => $familia['paciente_id'],
                ]);
        }
        
        $paciente_comorbidades = PacienteComorbidades::create([
            'paciente_id' => $paciente->id,
            'comorbidades_id' => $request->comorbidades_id
        ]);

        $atendimento_sinais = AtendimentoSinais::create([
            'atendimento_id' => $atendimento->id,
            'sinais_id' => $request->sinais_id
        ]);

        return response()->json([$paciente,$atendimento, $familiars, $paciente_comorbidades, $atendimento_sinais], 201);
    }
}
