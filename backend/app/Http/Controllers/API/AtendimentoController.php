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
    public function update(Request $request, $id)
    {
        $updateAtendimento = DB::transaction(function() use ($request, $id) {
            $atendimento = Atendimento::findOrFail($id);
            $atendimento->data_hora_ligacao = $request->atendimento_data_hora_ligacao;
            $atendimento->isolamento = $request->atendimento_isolamento;
            $atendimento->orientacao = $request->atendimento_orientacao;
            $atendimento->apetite = $request->atendimento_apetite;
            $atendimento->febre = $request->atendimento_febre;
            $atendimento->tosse = $request->atendimento_tosse;
            $atendimento->falta_de_ar = $request->atendimento_falta_de_ar;
            $atendimento->observacoes_gerais = $request->atendimento_observacoes_gerais;
            
            $pontos = 0;
            switch ($request->atendimento_febre) {
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
            switch ($request->atendimento_tosse) {
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
            switch ($request->atendimento_falta_de_ar) {
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
            $paciente = Paciente::findOrFail($request->atendimento_paciente_id);
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
            $atendimento->paciente_id = $request->atendimento_paciente_id;
            $atendimento->usuario_id = $request->atendimento_usuario_id;
            $atendimento->save();
            
            $atendimento_sinais[] = DB::table('atendimento_sinais')
                ->where('atendimento_sinais.atendimento_id',$id)
                ->get();
            
            if(isset($atendimento_sinais)){
              foreach($atendimento_sinais[0] as $as){
                    $a_s = PacienteComorbidades::findOrFail($as->id);
                    $a_s->delete();
                }
            }
            $sinais = $request->sinais;    
            if(isset($sinais)){
                    foreach($sinais as $s){
                    $a_s = AtendimentoSinais::create([
                        'atendimento_id'=>$atendimento->id,
                        'sinais_id'=>$s
                    ]);
                    $atendimentos_sinais[]= $a_s;
                }
            }
        
            
            $p = DB::table('pacientes')
                ->where('pacientes.id', $request->atendimento_paciente_id)
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
                if(isset($paciente_comorbidades)){
                foreach($paciente_comorbidades[0] as $pc){
                    $p_c = PacienteComorbidades::findOrFail($pc->id);
                    $p_c->delete();
                }
            }
            $paciente_comorbidades=[];
            $comorbidades = $request->comorbidades;
                if(isset($comorbidades)){
                foreach($comorbidades as $key=> $c){
                    $p_c = PacienteComorbidades::create([
                        'paciente_id' => $paciente->id,
                        'comorbidades_id' => $c
                    ]);
                    $paciente_comorbidades[] = $p_c;
                }
            }
            
            
            $familiares[] = DB::table('familiars')
            ->where('familiars.paciente_id', $paciente->id)
            ->get();
            if(isset($familiares)){
                foreach($familiares[0] as $f){
                    $fm = Familiar::findOrFail($f->id);
                    $fm->delete();
                }
            }
            $familiares = [];
            $familia = $request->familiares;
            if(isset($familia)){
                foreach($familia as $key=> $familiar)
                {   
                    $f = Familiar::create([
                        'nome'=> $familiar['nome'],
                        'sintomatico'=> $familiar['sintomatico'],
                        'exame'=> $familiar['exame'],
                        'paciente_id'=>$paciente->id
                    ]);
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

    public function store_atendimento_update_paciente(Request $request){
        $createAtendimento = DB::transaction(function() use ($request) {
            
            $atendimento = Atendimento::create([
                'data_hora_ligacao' => $request->atendimento_data_hora_ligacao,
                'isolamento' => $request->atendimento_isolamento,
                'orientacao' => $request->atendimento_orientacao,
                'apetite' => $request->atendimento_apetite,
                'febre' => $request->atendimento_febre,
                'tosse' => $request->atendimento_tosse,
                'falta_de_ar' => $request->atendimento_falta_de_ar,
                'observacoes_gerais' => $request->atendimento_observacoes_gerais,
                'paciente_id' => $request->atendimento_paciente_id,
                'usuario_id' => $request->atendimento_usuario_id
            ]);
            $pontos = 0;
            switch ($request->atendimento_febre) {
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
            switch ($request->atendimento_tosse) {
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
            switch ($request->atendimento_falta_de_ar) {
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
            $paciente = Paciente::findOrFail($request->atendimento_paciente_id);
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
            
            
            $sinais = $request->sinais;
            
            if(isset($sinais)){
                foreach($sinais as $sinal){
                    $atendimento_sinais[] = AtendimentoSinais::create([
                        'atendimento_id'=> $atendimento->id,
                        'sinais_id' => $sinal
                    ]);
                }
            }


            $paciente_comorbidades[] = DB::table('paciente_comorbidades')
            ->where('paciente_comorbidades.paciente_id',$paciente->id)
            ->get();
                if(isset($paciente_comorbidades)){
                foreach($paciente_comorbidades[0] as $pc){
                    $p_c = PacienteComorbidades::findOrFail($pc->id);
                    $p_c->delete();
                }
            }
            $paciente_comorbidades=[];
            $comorbidades = $request->comorbidades;
                if(isset($comorbidades)){
                foreach($comorbidades as $key=> $c){
                    $p_c = PacienteComorbidades::create([
                        'paciente_id' => $paciente->id,
                        'comorbidades_id' => $c
                    ]);
                    $paciente_comorbidades[] = $p_c;
                }
            }
            

            $familiares[] = DB::table('familiars')
                ->where('familiars.paciente_id', $paciente->id)
                ->get();
            if(isset($familiares)){
                foreach($familiares[0] as $f){
                    $fm = Familiar::findOrFail($f->id);
                    $fm->delete();
                }
            }
            $familiares = [];
            $familia = $request->familiares;
            if(isset($familia)){
                foreach($familia as $key=> $familiar)
                {   
                    $f = Familiar::create([
                        'nome'=> $familiar['nome'],
                        'sintomatico'=> $familiar['sintomatico'],
                        'exame'=> $familiar['exame'],
                        'paciente_id'=>$paciente->id
                    ]);
                    $familiares[$key]=$f;
                }
            }
            return response()->json([$atendimento, $paciente, $atendimento_sinais, $paciente_comorbidades, $familiares],201);
        });
        return response()->json($createAtendimento, 200);

    }

    
    public function atendimentos_por_data(){
        $hoje = Carbon::now();
      
        $atendimento_hoje = DB::table('atendimentos')
                            ->whereDate('data_hora_ligacao',$hoje)
                            ->count();

        $semana = Carbon::now()->subDays(7);

        $atendimento_semana = DB::table('atendimentos')
                            ->whereDate('data_hora_ligacao','>', $semana)
                            ->count();

        $mes = Carbon::now()->subDays(30);

        $atendimento_mes = DB::table('atendimentos')
                            ->whereDate('data_hora_ligacao','>', $mes)
                            ->count();

        return response()->json([$atendimento_hoje, $atendimento_semana, $atendimento_mes], 200);
        
    }

    public function listagem_diaria(){
        $hoje = Carbon::now()->startOfDay();
        $datas_nao_risco = [];
        $idoso = Carbon::now()->subYears(60);
        
        $idosos = DB::table('pacientes')
                        ->where('pacientes.data_nasc','<', $idoso)
                        ->select('pacientes.*');
        
        $grupo_nao_risco = DB::table('pacientes')
                        ->leftJoin('paciente_comorbidades', 'pacientes.id', '=', 'paciente_comorbidades.paciente_id')
                        ->whereNull('paciente_comorbidades.comorbidades_id')
                        ->where('pacientes.isolamento_ate','>',$hoje)
                        ->where('pacientes.data_nasc','>', $idoso)
                        ->select('pacientes.*')
                        ->get();
        
        $grupo_risco = DB::table('pacientes')
                        ->join('paciente_comorbidades', 'pacientes.id', '=', 'paciente_comorbidades.paciente_id')
                        ->whereNotNull('paciente_comorbidades.comorbidades_id')
                        ->where('pacientes.isolamento_ate','>',$hoje)
                        ->union($idosos)
                        ->select('pacientes.*')
                        ->get();
        
        $atendidos = DB::table('atendimentos')
                        ->whereDate('atendimentos.data_hora_ligacao', $hoje)
                        ->select('atendimentos.*')
                        ->get();

        $id_atendimentos = [];
        
        for($i=0; $i<count($atendidos); $i++){
            $id_atendimentos[]=$atendidos[$i]->paciente_id;
        }

        $risco=[];
        foreach($grupo_risco as $gr){
            if (in_array($gr->id, $id_atendimentos)) { 
                
            }else{
                $risco[]=$gr;
            }
        }
        
        $atendidos_ontem = DB::table('atendimentos')
                        ->whereDate('atendimentos.data_hora_ligacao', $hoje->subDay(1))
                        ->select('atendimentos.*')
                        ->get();

        $id_atendimentos_ontem=[];
        for($i=0; $i<count($atendidos_ontem); $i++){
            $id_atendimentos_ontem[]=$atendidos_ontem[$i]->paciente_id;
        }
        
        $nao_risco=[];
        foreach($grupo_nao_risco as $gnp){
            if (in_array($gnp->id, $id_atendimentos)||in_array($gnp->id, $id_atendimentos_ontem)) { 
                
            }else{
                $nao_risco[]=$gnp;
            }
        }
        return response()->json([$nao_risco, $risco], 200);
        
    }
    
}
