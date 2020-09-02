<?php

namespace App\Http\Controllers\API;

use App\Atendimento;
use App\Sinais;
use App\PacienteComorbidades;
use App\AtendimentoSinais;
use App\Comorbidades;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AtendimentoFormRequest;
use App\Paciente;
use Carbon\Carbon;
use App\Exports\AtendimentosExport;
use App\Familiar;
use Maatwebsite\Excel\Excel;
use Illuminate\Support\Facades\DB;

class AtendimentoController extends Controller
{
    private $excel;

    public function __construct(Excel $excel){
        $this->excel = $excel;
    }

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
        $pontos = 0;
        switch ($request->febre) {
            case 'ausente':
                $pontos+=1;
                break;
            case 'pico_baixo' :
                $pontos+=2;
                break;
            case 'persistente':
                $pontos+=3;
                break;
        }
        switch ($request->tosse) {
            case 'ausente':
                $pontos+=1;
                break;
            case 'fala_sem_tossir':
                $pontos+=2;
                break;
            case'fala_tossindo':
                $pontos+=3;
                break;
        }
        switch ($request->falta_de_ar) {
            case 'ausente':
                $pontos+=1;
                break;
            case 'presente_ao_esforco':
                $pontos+=2;
                break;
            case 'intensa_no_repouso':
                $pontos+=3;
                break;
        }
        $paciente = Paciente::findOrFail($request->paciente_id);
        $idade = Carbon::parse($paciente['data_nasc'])->age;
        if($idade<30){
            $pontos+=1;
        }
        if($idade>=30 && $idade<60){
            $pontos+=2;
        }
        if($pontos>=60){
            $pontos+=3;
        }
               
        if($pontos<=6){
            $atendimento->orientacao_conduta = 'manter_isolamento_domiciliar'; 
        }
        if($pontos>=7 && $pontos<=9){
            $atendimento->orientacao_conduta = 'encaminhar_unidade_sintomatica'; 
        }
        if($pontos>=10){
            $atendimento->orientacao_conduta = 'encaminhar_SAMU';
        }
        
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
        
        $sinais[] = DB::table('sinais')
            ->join('atendimento_sinais','sinais.id','=','atendimento_sinais.sinais_id')
            ->where('atendimento_sinais.atendimento_id',$id)
            ->get();
        
        $paciente = DB::table('pacientes')
            ->where('pacientes.id', $id)
            ->first();
        
        $comorbidades[] = DB::table('comorbidades',)
            ->join('paciente_comorbidades','comorbidades.id','=','paciente_comorbidades.comorbidades_id')
            ->where('paciente_comorbidades.paciente_id', $paciente->id)
            ->get();
        
        $familiares[] = DB::table('familiars')
            ->where('familiars.paciente_id', $paciente->id)
            ->get();

        return response()->json([$atendimento, $sinais, $paciente, $comorbidades, $familiares], 200);
    }

    public function show_atendimento_sinais($id)
    {
        $atendimento = Atendimento::findOrFail($id);
        $sinais = [];
        $sinais[] = DB::table('sinais')
            ->join('atendimento_sinais','sinais.id','=','atendimento_sinais.sinais_id')
            ->where('atendimento_sinais.atendimento_id',$id)
            ->get();
        
        return response()->json([$atendimento, $sinais], 200);
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
        $updateAtendimento = DB::transaction(function() use ($request, $id) {
        $atendimento = Atendimento::findOrFail($id);
        $atendimento->data_hora_ligacao = $request->data_hora_ligacao;
        $atendimento->isolamento = $request->isolamento;
        $atendimento->orientacao = $request->orientacao;
        $atendimento->apetite = $request->apetite;
        $atendimento->febre = $request->febre;
        $atendimento->tosse = $request->tosse;
        $atendimento->falta_de_ar = $request->falta_de_ar;
        $atendimento->observacoes_gerais = $request->observacoes_gerais;
        
        $pontos = 0;
        switch ($request->febre) {
            case 'ausente':
                $pontos+=1;
                break;
            case 'pico_baixo' :
                $pontos+=2;
                break;
            case 'persistente':
                $pontos+=3;
                break;
        }
        switch ($request->tosse) {
            case 'ausente':
                $pontos+=1;
                break;
            case 'fala_sem_tossir':
                $pontos+=2;
                break;
            case'fala_tossindo':
                $pontos+=3;
                break;
        }
        switch ($request->falta_de_ar) {
            case 'ausente':
                $pontos+=1;
                break;
            case 'presente_ao_esforco':
                $pontos+=2;
                break;
            case 'intensa_no_repouso':
                $pontos+=3;
                break;
        }
        $paciente = Paciente::findOrFail($request->paciente_id);
        $idade = Carbon::parse($paciente['data_nasc'])->age;
        if($idade<30){
            $pontos+=1;
        }
        if($idade>=30 && $idade<60){
            $pontos+=2;
        }
        if($pontos>=60){
            $pontos+=3;
        }
               
        if($pontos<=6){
            $atendimento->orientacao_conduta = 'manter_isolamento_domiciliar'; 
        }
        if($pontos>=7 && $pontos<=9){
            $atendimento->orientacao_conduta = 'encaminhar_unidade_sintomatica'; 
        }
        if($pontos>=10){
            $atendimento->orientacao_conduta = 'encaminhar_SAMU';
        }
        $atendimento->paciente_id = $request->paciente_id;
        $atendimento->usuario_id = $request->usuario_id;
        $atendimento->save();
        
        $atendimento_sinais[] = DB::table('atendimento_sinais')
            ->where('atendimento_sinais.atendimento_id',$id)
            ->get();
        
        
        $sinais = $request->sinais;
        foreach($atendimento_sinais[0] as $key=> $a_s)
        {
            if(isset($sinais[$key]))
            {
                $as = AtendimentoSinais::findOrFail($a_s->id);
                $as->sinais_id = $sinais[$key]['id'];
                $as->save();
                $atendimento_sinais[$key]=$as;
            }
        }
    
        
        $p = DB::table('pacientes')
            ->where('pacientes.id', $request->paciente_id)
            ->first();
        
        $paciente = Paciente::findOrFail($p->id);
        $paciente->nome = $request->paciente_nome;
        $paciente->cep = $request->paciente_cep;
        $endereco = \Correios::cep($request->paciente_cep);
        if(empty($endereco)) {
            return response("Endereço não encontrado.", 500);
        }
        $paciente->logradouro = $endereco['logradouro'];
        $paciente->bairro = $endereco['bairro'];
        $paciente->cidade = $endereco['cidade'];
        $paciente->estado = $endereco['uf'];
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
        

        $paciente_comorbidades[] = DB::table('paciente_comorbidades')
            ->where('paciente_comorbidades.paciente_id',$paciente->id)
            ->get();
        
        $comorbidades = $request->comorbidades;
        foreach($paciente_comorbidades[0] as $key=> $p_c)
        {
            if(isset($comorbidades[$key]))
            {
                $pc = PacienteComorbidades::findOrFail($p_c->id);
                $pc->comorbidades_id = $comorbidades[$key]['id'];
                $pc->save();
                $paciente_comorbidades[$key]=$pc;
            }
        }
        
        
        $familiares[] = DB::table('familiars')
            ->where('familiars.paciente_id', $paciente->id)
            ->get();
            
        $familia = $request->familiares;
        foreach($familiares[0] as $key=> $familiar)
        {
            if(isset($familia[$key]))
            {
                $f = Familiar::findOrFail($familiar->id);
                $f->nome = $familia[$key]['familiares_nome'];
                $f->sintomatico = $familia[$key]['familiares_sintomatico'];
                $f->exame = $familia[$key]['familiares_exame'];
                $f->save();
                $familiares[$key]=$f;
            }
        }
        
        return response()->json([$atendimento, $atendimento_sinais, $paciente, $paciente_comorbidades, $familiares], 200);
        });
        return response()->json($updateAtendimento, 200);
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

    public function export_excel() 
    {
        return $this->excel->download(new AtendimentosExport, 'atendimentos.xlsx');
    }
    public function export_pdf() 
    {
        return $this->excel->download(new AtendimentosExport, 'atendimentos.pdf', Excel::DOMPDF);
    }
}
