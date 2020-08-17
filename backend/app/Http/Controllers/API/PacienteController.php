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
        if(empty($endereco)) {
            return response("Endereço não encontrado.", 500);
        }

        $paciente = Paciente::create([
            'logradouro' => $endereco['logradouro'],
            'bairro' => $endereco['bairro'],
            'cidade' => $endereco['cidade'],
            'estado' => $endereco['uf'],
            'nome' => $request->paciente_nome,
            'cep' => $request->paciente_cep,
            'numero' => $request->paciente_numero,
            'telefone' => $request->paciente_telefone,
            'cns' => $request->paciente_cns,
            'data_nasc' => $request->paciente_data_nasc,
            'obito' => $request->paciente_obito,
            'primeira_avaliacao_medica' => $request->paciente_primeira_avaliacao_medica,
            'isolamento_ate' => $request->paciente_isolamento_ate,
            'data_inicio_sintomas' => $request->paciente_data_inicio_sintomas,
            'data_coleta_exames' => $request->paciente_data_coleta_exames,
            'unidade_sintomatica_id' => $request->paciente_unidade_sintomatica_id,
            'convenio' => $request->paciente_convenio,
            'unidade_saude_id' => $request->paciente_unidade_saude_id,
            'tipo_exame' => $request->paciente_tipo_exame,
            'data_resultado' => $request->paciente_data_resultado,
            'resultado_exame' => $request->paciente_resultado_exame,
            'grupo_risco' => $request->paciente_grupo_risco
        ]);
        
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
        $atendimento->paciente_id = $paciente->id;
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

        $comorbidades = $request->comorbidades;
        $paciente_comorbidades = [];
        foreach($comorbidades as $comorbidade)
        {
            $paciente_comorbidades[] = PacienteComorbidades::create([
                'paciente_id' => $paciente->id,
                'comorbidades_id' => $comorbidade['id']
            ]);
        }

        $sinais = $request->sinais;
        $atendimento_sinais = [];
        foreach($sinais as $sinal)
        {
            $atendimento_sinais[] = AtendimentoSinais::create([
                'atendimento_id' => $atendimento->id,
                'sinais_id' => $sinal['id']
            ]);
        }

        return response()->json([$paciente,$atendimento, $familiars, $paciente_comorbidades, $atendimento_sinais], 201);
    }

    public function cep(Request $request)
    {
        $endereco = \Correios::cep($request->cep);
        return response()->json($endereco, 200);
    }
}
